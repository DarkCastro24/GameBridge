<?php
// ============================================================
//  API: google_callback.php
//  Ruta: app/api/public/google_callback.php
//  Recibe el token JWT de Google, lo valida y autentica al cliente
// ============================================================
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../helpers/google_config.php');
require_once('../../models/clientes.php');

session_start();

$result = array('status' => 0, 'message' => null, 'exception' => null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {

    $token = $_POST['credential'];

    // ----------------------------------------------------------
    // 1. Validar el token con la API de Google (sin librerías externas)
    // ----------------------------------------------------------
    $googleResponse = file_get_contents(
        'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($token)
    );

    if (!$googleResponse) {
        $result['exception'] = 'No se pudo verificar el token con Google';
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
        exit;
    }

    $payload = json_decode($googleResponse, true);

    // Verificar que el token sea para nuestra aplicación
    if (!isset($payload['aud']) || $payload['aud'] !== GOOGLE_CLIENT_ID) {
        $result['exception'] = 'Token inválido';
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
        exit;
    }

    // Verificar que el token no haya expirado
    if (!isset($payload['exp']) || $payload['exp'] < time()) {
        $result['exception'] = 'Token expirado, intenta de nuevo';
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
        exit;
    }

    // ----------------------------------------------------------
    // 2. Extraer datos del perfil de Google
    // ----------------------------------------------------------
    $googleId    = $payload['sub'];           // ID único de Google
    $correo      = $payload['email'];
    $nombres     = $payload['given_name']  ?? 'Usuario';
    $apellidos   = $payload['family_name'] ?? 'Google';
    $verificado  = $payload['email_verified'] ?? false;

    if (!$verificado) {
        $result['exception'] = 'El correo de Google no está verificado';
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
        exit;
    }

    // ----------------------------------------------------------
    // 3. Buscar si el cliente ya existe en la BD por correo
    // ----------------------------------------------------------
    $cliente = new Clientes;

    if ($cliente->checkUser($correo)) {
        // Cliente existe → verificar que esté activo
        if (!$cliente->checkState($correo)) {
            $result['exception'] = 'Tu cuenta se encuentra bloqueada';
            header('content-type: application/json; charset=utf-8');
            print(json_encode($result));
            exit;
        }
        // Actualizar historial de acceso
        $cliente->historialCliente();
        // Restablecer intentos fallidos por si los tenía
        $cliente->intentosCliente(0);

    } else {
        // ----------------------------------------------------------
        // 4. Cliente no existe → crearlo automáticamente
        // ----------------------------------------------------------
        // Generamos valores por defecto para campos requeridos
        $duiAleatorio = '000000000';   // DUI placeholder (no aplica para cuentas Google)
        $claveAleatoria = bin2hex(random_bytes(16)); // Clave aleatoria segura (nunca la usará)

        $sql = 'INSERT INTO clientes(estado, nombres, apellidos, dui, correo_electronico, clave, fecharegistro, fechaclave, intentos)
                VALUES (1, ?, ?, ?, ?, ?, default, default, 0)';
        $hash = password_hash($claveAleatoria, PASSWORD_DEFAULT);
        $params = array($nombres, $apellidos, $duiAleatorio, $correo, $hash);

        if (!Database::executeRow($sql, $params)) {
            $result['exception'] = Database::getException() ?: 'Error al registrar usuario con Google';
            header('content-type: application/json; charset=utf-8');
            print(json_encode($result));
            exit;
        }

        // Volver a cargar los datos del cliente recién creado
        if (!$cliente->checkUser($correo)) {
            $result['exception'] = 'Error al cargar datos del usuario creado';
            header('content-type: application/json; charset=utf-8');
            print(json_encode($result));
            exit;
        }

        $cliente->historialCliente();
    }

    // ----------------------------------------------------------
    // 5. Iniciar sesión → asignar variables de sesión
    // ----------------------------------------------------------
    $_SESSION['idcliente'] = $cliente->getId();
    $_SESSION['cliente']   = $nombres . ' ' . $apellidos;
    $_SESSION['correo']    = $correo;
    $_SESSION['google']    = true; // Flag para saber que inició con Google

    $result['status']  = 1;
    $result['message'] = 'Sesión iniciada correctamente con Google';

} else {
    $result['exception'] = 'Solicitud inválida';
}

header('content-type: application/json; charset=utf-8');
print(json_encode($result));