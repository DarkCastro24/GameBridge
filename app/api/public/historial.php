<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/historial.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $categoria = new Categorias;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        case 'readAll'://metodo para ver las valoraciones de un cliente
            if ($result['dataset'] = $categoria->readAll($_SESSION['idcliente'])) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen categorías para mostrar';
                }
            }
            break;
            case 'create'://metodo para agregar una valoracion
                $_POST = $categoria->validateForm($_POST);
                    if ($categoria->setDetalle($_POST['txtId'])) {
                        if ($categoria->setCalificacion($_POST['txtCalificacion'])) {
                            if ($categoria->setComentario($_POST['txtComentario'])) {
                                if ($categoria->createRow()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Reseña ingresada correctamente';
                                } else {
                                    $result['exception'] = Database::getException();;           
                                }                                                                                              
                            } else {
                                $result['exception'] = 'Comentario incorrecto';
                            }                                                                                     
                        } else {
                            $result['exception'] = 'Calificacion incorrecta';
                        }
                    } else {
                        $result['exception'] = 'ID del detalle incorrecto';
                    }        
                break;
                case 'readOne'://metodo para cargar una valoracion 
                    $_POST = $categoria->validateForm($_POST);
                    if ($categoria->setId($_POST['txtIdX'])) {
                        if ($result['dataset'] = $categoria->readOne()) {
                            $result['status'] = 1;
                        } else {
                            if (Database::getException()) {
                                $result['exception'] = Database::getException();
                            } else {
                                $result['exception'] = 'No existe reseña de este producto';
                            }
                        }
                    } else {
                        $result['exception'] = 'Producto incorrecto';
                    }
                    break;
                    case 'update'://metodo para acualizar una valoracion
                        $_POST = $categoria->validateForm($_POST);
                        if ($categoria->setDetalle($_POST['txtIdx'])) {
                            if ($categoria->setCalificacion($_POST['txtCalificacion2'])) {
                                if ($categoria->setComentario($_POST['txtComentario2'])) {
                                    if ($categoria->updateRow()) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Reseña modificada correctamente';
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }                                                                                             
                                } else {
                                    $result['exception'] = 'Comentario incorrecto';
                                }                                                                                     
                            } else {
                                $result['exception'] = 'Calificacion incorrecta';
                            }
                        } else {
                            $result['exception'] = 'ID del detalle incorrecto';
                        }        
                    break;
                    case 'delete'://metodo para eliminar una valoracion
                        $_POST = $categoria->validateForm($_POST);
                        if ($categoria->setId($_POST['txtId'])) {
                            if ($data = $categoria->readOne()) {
                                if ($categoria->deleteRow()) {
                                    $result['status'] = 1;
                                    $result['message'] = 'Reseña eliminada correctamente';
                                } else {
                                    $result['exception'] = Database::getException();
                                }
                            } else {
                                $result['exception'] = 'Reseña inexistente';
                            }
                        } else {
                            $result['exception'] = 'Reseña incorrecto';
                        }
                    break;
            default:
                $result['exception'] = 'Acción no disponible';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
