<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/pedidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $pedido = new Pedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['idcliente'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'create': //metodo para agregar direcciones
                $_POST = $pedido->validateForm($_POST);
                if ($pedido->setDireccion($_POST['direccion'])) {
                    if ($pedido->setCodigoPostal($_POST['codigopostal'])) {
                        if ($pedido->setTelefonoFijo($_POST['telefono'])) {
                            if ($pedido->crearDireccion()) {
                                $result['status'] = 1;
                                $result['message'] = 'Direccion registrada correctamente';
                            } else {
                                $result['exception'] = Database::getException();;
                            }
                        } else {
                            $result['exception'] = 'Telefono incorrecto';
                        }
                    } else {
                        $result['exception'] = 'codigo incorrecto';
                    }
                } else {
                    $result['exception'] = 'direccion incorrecta';
                }

                break;
            case 'readAll': //metodo para cargar las direcciones
                $_POST = $pedido->validateForm($_POST);
                if ($result['dataset'] = $pedido->readAll()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen registros';
                    }
                }
                break;
            case 'update': //metodo para establecer la direccion de destino del pedido
                $_POST = $pedido->validateForm($_POST);
                if ($pedido->setcmbDireccion($_POST['cmbDireccion'])) {
                    if ($pedido->updateDireccion()) {
                        $result['status'] = 1;
                        $result['message'] = 'Dirección establecida correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {

                    $result['exception'] = 'Estado incorrecto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando un cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'createDetail':
                $result['exception'] = 'Debe iniciar sesión para agregar el producto al carrito';
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
