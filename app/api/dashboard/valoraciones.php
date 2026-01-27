<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/valoraciones.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $producto = new Clientes;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cargar todos datos de la tabla
            case 'readAll': 
                 // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $producto->cargarDatos()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay valoraciones registradas';
                    }
                }
                break;
            // Caso para realizar busqueda filtrada
            case 'search': 
                // Obtenemos el post para tener acceso a los inputs del formulario
                 $_POST = $producto->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo
                    if ($result['dataset'] = $producto->busquedaFiltrada($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        // Comparamos el numero de filas obtenidas
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
                    } else {
                        // Se ejecuta si existe algun error en la base de datos 
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay coincidencias';
                        }
                    }
                } else {
                    $result['exception'] = 'Ingrese un valor para buscar';
                }
                break;
            // Caso para ingresar registros a la base de datos
            case 'create': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $producto->validateForm($_POST);
                    if ($producto->setDetalle($_POST['cmbDetalle'])) {
                        if ($producto->setCalificacion($_POST['txtCalificacion'])) {
                            if ($producto->setComentario($_POST['txtComentario'])) {
                                if ($producto->setEstado(isset($_POST['estado_producto']) ? 1 : 0)) {
                                    // Ejecutamos la funcion del modelo 
                                    if ($producto->ingresarDatos()) {
                                        $result['status'] = 1;
                                    } else {
                                        $result['exception'] = Database::getException();;           
                                    } 
                                } else {
                                    $result['exception'] = 'Estado incorrecto';
                                }                                                                                                
                            } else {
                                $result['exception'] = 'Dui incorrecto';
                            }                                                                                     
                        } else {
                            $result['exception'] = 'Apellido incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Seleccione un estado';
                    }        
                break;
            // Caso para cargar los datos de un registro en especifico
            case 'readOne': 
                // Obtenemos el valor del id
                if ($producto->setId($_POST['id'])) {
                    // Ejecutamos la funcion del modelo
                    if ($result['dataset'] = $producto->cargarFila()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                                $result['exception'] = Database::getException();
                        } else {
                                $result['exception'] = 'Producto inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'Producto incorrecto';
                }
                break;
            // Caso para modificar registros en la base de datos
            case 'update': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $producto->validateForm($_POST);
                if ($producto->setId($_POST['txtId'])) {
                    if ($producto->setDetalle($_POST['cmbDetalle'])) {
                        if ($producto->setCalificacion($_POST['txtCalificacion'])) {
                            if ($producto->setComentario($_POST['txtComentario'])) {
                                if ($producto->setEstado(isset($_POST['estado_producto']) ? 1 : 0)) {
                                    // Ejecutamos la funcion del modelo
                                    if ($producto->actualizarDatos()) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Reseña modificada correctamente';
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                } else {
                                    $result['exception'] = 'Estado incorrecto';
                                }                                                                                                
                            } else {
                                $result['exception'] = 'Dui incorrecto';
                            }                                                                                     
                        } else {
                            $result['exception'] = 'Apellido incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Seleccione un estado';
                    } 
                } else {
                    $result['exception'] = 'Cliente incorrecto';
                }                                     
                break;
            // Caso para eliminar registros en la base de datos
            case 'delete': 
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $producto->validateForm($_POST);
                // Obtenemos el valor del id
                if ($producto->setId($_POST['txtId'])) {
                    if ($data = $producto->cargarFila()) {
                        // Ejecutamos la funcion del modelo 
                        if ($producto->eliminarDatos()) {
                            $result['status'] = 1;
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Cliente inexistente';
                    }
                } else {
                    $result['exception'] = 'Cliente incorrecto';
                }
                break;
            // Si el caso no coincide con ninguno de los casos anteriores se muestra el siguiente mensaje
            default: 
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
