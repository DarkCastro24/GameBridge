<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Iniciar sesión', 'public');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/public_login.css" />
    <!-- Definir constantes y funciones ANTES de que cargue el SDK -->
    <script>
        const API_GOOGLE = '../../app/api/public/google_callback.php';

        // Función para decodificar el JWT de Google
        function decodeJWT(token) {
            try {
                const parts = token.split('.');
                if (parts.length !== 3) {
                    console.error('❌ Token JWT inválido');
                    return null;
                }
                return JSON.parse(atob(parts[1]));
            } catch (error) {
                console.error('❌ Error decodificando JWT:', error);
                return null;
            }
        }

        // Función manejador de Google Login
        function handleGoogleLogin(googleResponse) {
            if (!googleResponse || !googleResponse.credential) {
                console.error('❌ No se recibió respuesta de Google');
                sweetAlert(2, 'No se recibió respuesta de Google', null);
                return;
            }

            const token = googleResponse.credential;
            const payload = decodeJWT(token);

            if (!payload) {
                sweetAlert(2, 'Token de Google inválido', null);
                return;
            }

            // 🔍 DEBUG: Mostrar los datos del payload de Google en la consola
            console.log('%c📋 GOOGLE LOGIN - Datos recibidos de Google', 'color: #4CAF50; font-weight: bold; font-size: 14px;');
            console.log('Email:', payload.email);
            console.log('Nombre:', payload.given_name);
            console.log('Apellido:', payload.family_name);
            console.log('Email verificado:', payload.email_verified);
            console.log('Google ID:', payload.sub);
            console.log('Payload completo:', payload);

            // Preparamos el FormData con el token para enviarlo al backend
            const formData = new FormData();
            formData.append('credential', token);

            console.log('%c📤 Enviando token a la API...', 'color: #2196F3; font-weight: bold;');
            console.log('URL API:', API_GOOGLE);
            console.log('Método: POST');

            fetch(API_GOOGLE, {
                method: 'POST',
                body: formData
            }).then(function (request) {
                if (request.ok) {
                    request.json().then(function (response) {
                        console.log('%c✅ Respuesta de la API de Google:', 'color: #4CAF50; font-weight: bold;');
                        console.log(response);

                        if (response.status) {
                            console.log('%c🎉 Login exitoso, redirigiendo...', 'color: #4CAF50; font-weight: bold;');
                            sweetAlert(1, response.message, 'index.php');
                        } else {
                            console.error('❌ Error en login:', response.exception);
                            sweetAlert(2, response.exception, null);
                        }
                    });
                } else {
                    console.error('❌ Error HTTP: ' + request.status + ' ' + request.statusText);
                    sweetAlert(2, 'Error en la conexión: ' + request.status, null);
                }
            }).catch(function (error) {
                console.error('❌ Error de red:', error);
                sweetAlert(2, 'Error de conexión al iniciar sesión con Google', null);
            });
        }

        window.onGoogleLibraryLoad = function () {
            console.log('SDK de Google cargado, inicializando...');
            google.accounts.id.initialize({
                client_id: GOOGLE_CLIENT_ID_VALUE,
                callback: handleGoogleLogin
            });
            google.accounts.id.renderButton(
                document.getElementById('google-signin-btn'),
                {
                    type: 'standard',
                    size: 'large',
                    theme: 'outline',
                    text: 'signin_with',
                    shape: 'rectangular',
                    logo_alignment: 'left',
                    locale: 'es'
                }
            );
        };
    </script>
    <!-- Google Sign-In SDK -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>

<body>
    <div class="row login">
        <div class="col s12 m12 l4 push-l3 offset-l1">
            <div class="card">
                <div class="card-action white-text center-align">
                    <h4>Inicio de sesión</h4>
                    <img src="../../resources/img/brand/Logo.png" width="170" height="120">
                </div>
                <div class="card-content">
                    <form method="post" id="session-form">
                        <div class="form-field">
                            <label for="email">Correo electrónico</label>
                            <input id="email" type="text" name="email" class="validate" autocomplete="off" required>
                        </div><br>
                        <div class="form-field">
                            <label for="clave">Contraseña</label>
                            <input id="clave" type="password" name="clave" class="validate" autocomplete="off" required>
                        </div><br>
                        <div class="form-field center-align">
                            <a><button type="submit" class="button"><span>Ingresar</span></button></a>
                        </div>
                        <div class="form-field center-align">
                            <a href="codigo.php">¿Has olvidado tu contraseña?</a>
                        </div>
                    </form>

                    <!-- Separador visual -->
                    <div class="google-divider">
                        <span>o continúa con</span>
                    </div>

                    <!-- Botón de Google Sign-In -->
                    <div class="google-btn-container center-align">
                        <div id="google-signin-btn"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

<!-- Inyectar el Client ID de Google como variable JS accesible por login.js -->
<script>
    const GOOGLE_CLIENT_ID_VALUE = '<?php echo GOOGLE_CLIENT_ID; ?>';
</script>

<?php
Public_Page::footerTemplate('login.js');
?>