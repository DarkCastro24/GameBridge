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
            case 'createDetail': //metodo que crea el detalle de factura y la factura si no existe
                if ($pedido->startOrder()) {
                    $_POST = $pedido->validateForm($_POST);
                    if ($pedido->setProducto($_POST['txtId'])) {
                        if ($pedido->setCantidad($_POST['cantidad_producto'])) {
                            if ($pedido->setPrecio($_POST['precio'])) {
                                if ($pedido->createDetail()) {
                                    if ($dataMaterial = $pedido->readOne()) {
                                        if ($pedido->setCantidadStock($dataMaterial['cantidad'])) {
                                            if ($pedido->restarStock()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Agregado al carrito correctamente.';
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = 'Cantidad stock incorrecta.';
                                        }
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                } else {
                                    $result['exception'] = 'Ocurrió un problema al agregar el producto';
                                }
                            } else {
                                $result['exception'] = 'Precio incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Cantidad incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Producto incorrecto';
                    }
                } else {
                    $result['exception'] = 'Ocurrió un problema al obtener el pedido';
                }
                break;
            case 'create': // metodo para crear un direccion
                $_POST = $pedido->validateForm($_POST);
                if ($pedido->setTelefonoFijo($_POST['telefono'])) {
                    if ($pedido->setCodigoPostal($_POST['codigopostal'])) {
                        if ($pedido->setDireccion($_POST['direccion'])) {
                            if ($pedido->crearDireccion()) {
                                $result['status'] = 1;
                                $result['message'] = 'Direccion agregada correctamente';
                            } else {
                                $result['exception'] = Database::getException();;
                            }
                        } else {
                            $result['exception'] = 'Direccion incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Codigo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Telefono incorrecto';
                }
                break;
            case 'readAllParam': // METODO READ CON PARAMETRO PARA MODAL
                $_POST = $pedido->validateForm($_POST);
                if ($result['dataset'] = $pedido->cargarDatosParam($_SESSION['idcliente'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen registros';
                    }
                }
                break;
            case 'delete': //metodo usado para eliminar una direccion
                $_POST = $pedido->validateForm($_POST);
                if ($pedido->setId($_POST['txtIdx'])) {
                    if ($pedido->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Direccion eliminada correctamente';
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'Cliente incorrecto';
                }
                break;
            case 'readOrderDetail': //metodo para cargar el carrito de compras del cliente
                if ($pedido->setCliente($_SESSION['idcliente'])) {
                    if ($pedido->startOrder()) {
                        if ($result['dataset'] = $pedido->readOrderDetail()) {
                            $result['status'] = 1;
                            $_SESSION['idfactura'] = $pedido->getIdPedido();
                        } else {
                            if (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'No tiene productos en el carrito';
                            }
                        }
                    } else {
                        $result['exception'] = 'Debe agregar un producto al carrito';
                    }
                } else {
                    $result['exception'] = 'Cliente incorrecto';
                }
                break;
            case 'readOneMaterial':  //metodo para obtener datos del producto
                $_POST = $pedido->validateForm($_POST);
                if ($pedido->setProducto($_POST['idproducto'])) {
                    if ($result['dataset'] = $pedido->readOne()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = Database::getException();
                    }
                } else {
                    $result['exception'] = 'id incorrecto';
                }
                break;
            case 'updateStock': //metodo para actualizar las cantidades del stock
                if ($pedido->setProducto($_POST['idproducto'])) {
                    if ($pedido->setIdDetalle($_POST['id_detalle'])) {
                        if ($pedido->updateStock($_POST['stockBodega'])) {
                            if ($pedido->updateOrderStock($_POST['stockPedido'])) {
                                $result['status'] = 1;
                                $result['message'] = 'Cantidad actualizada correctamente.';
                            } else {
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Id material incorrecto';
                    }
                } else {
                    $result['exception'] = 'Id material incorrecto';
                }
                break;
            case 'deleteDetail': //metodo para eliminar un producto de un carrito
                if ($pedido->setIdPedido($_SESSION['idfactura'])) {
                    if ($pedido->setIdDetalle($_POST['id_detalle'])) {
                        if ($pedido->setCantidad($_POST['cantidad_producto'])) {
                            if ($pedido->setProducto($_POST['idproducto'])) {
                                if ($data = $pedido->readOne()) {
                                    if ($pedido->setCantidadStock($data['cantidad'])) {
                                        if ($pedido->restoreStock()) {
                                            if ($pedido->deleteDetail()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Producto eliminado del carrito con exito.';
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = 'No se han podido restaurar las cantidades antes de eliminar el registro.';
                                        }
                                    } else {
                                        $result['exception'] = 'Cantidad incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'No se cargaron los datos';
                                }
                            } else {
                                $result['exception'] = 'Producto incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Cantidad incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Detalle incorrecto';
                    }
                } else {
                    $result['exception'] = 'Pedido incorrecto';
                }
                break;
            case 'finishOrder': //metodo para finalizar un pedido
                if ($pedido->finishOrder()) {
                    $result['status'] = 1;
                    $result['message'] = 'Pedido finalizado correctamente';
                } else {
                    $result['exception'] = 'Ocurrió un problema al finalizar el pedido';
                }
                break;
            case 'readAll': //metodo para cargar las direcciones de un cliente
                $_POST = $pedido->validateForm($_POST);
                if ($result['dataset'] = $pedido->cargarDatosParam($_SESSION['idcliente'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen registros';
                    }
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
