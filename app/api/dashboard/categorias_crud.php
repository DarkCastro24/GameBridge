<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/categorias_producto.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $categorias = new Categorias_Producto;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    if (isset($_SESSION['idusuario'])) {
        switch ($_GET['action']) {
            // Metodo para cargar todos los datos
            case 'readAll':
                // Ejecutamos el metodo para cargar los datos 
                if ($result['dataset'] = $categorias->readAll()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay categrias registrados';
                    }
                }
                break;
            // Metodo para ejecutar la busqueda filtrada
            case 'search':
                $_POST = $categorias->validateForm($_POST);
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion que realiza la busqueda filtrada
                    if ($result['dataset'] = $categorias->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        // Obtenemos el numero de filas retornadas por la consulta 
                        $rows = count($result['dataset']);
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
                    } else {
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
            // Caso para ingresar registros en la base de datos
            case 'create':
                $_POST = $categorias->validateForm($_POST);

                // Validar presencia de campos del save-form
                if (!isset($_POST['txtusuario'], $_POST['cmbTipo'], $_POST['txtDescripcion'])) {
                    $result['exception'] = 'Faltan campos del formulario (nombre, sección o descripción)';
                    break;
                }

                if ($categorias->setCategoria($_POST['txtusuario'])) {
                    if ($categorias->setSeccion($_POST['cmbTipo'])) {
                        if ($categorias->setDescripcion($_POST['txtDescripcion'])) {
                            if (is_uploaded_file($_FILES['archivo_categoria']['tmp_name'])) {
                                if ($categorias->setImagen($_FILES['archivo_categoria'])) {
                                    if ($categorias->createRow()) {
                                        $result['status'] = 1;
                                        // Guardamos la imagen dentro de la carpeta del proyecto
                                        if ($categorias->saveFile($_FILES['archivo_categoria'], $categorias->getRuta(), $categorias->getImagen())) {
                                            $result['message'] = 'Categoría registrada correctamente';
                                        } else {
                                            $result['message'] = 'Categoría registrada pero no se guardó la imagen';
                                        }
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                } else {
                                    $result['exception'] = 'La imagen debe ser de al menos 500x500 píxeles';
                                }
                            } else {
                                $result['exception'] = 'Error al subir la imagen';
                            }
                        } else {
                            $result['exception'] = 'Descripción incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Sección incorrecta';
                    }
                } else {
                    $result['exception'] = 'Categoría incorrecta';
                }
                break;


            case 'readOne':
                $_POST = $categorias->validateForm($_POST);

                if (!isset($_POST['txtId'])) {
                    $result['exception'] = 'Falta el identificador del registro';
                    break;
                }

                if ($categorias->setIdcategoria($_POST['txtId'])) {
                    if ($result['dataset'] = $categorias->readOne()) {
                        $result['status'] = 1;
                    } else {
                        $result['exception'] = Database::getException() ?: 'Categoría inexistente';
                    }
                } else {
                    $result['exception'] = 'Id de categoría incorrecto';
                }
                break;


            case 'update':
                $_POST = $categorias->validateForm($_POST);

                // Validar presencia de campos del save-form
                if (!isset($_POST['txtId'], $_POST['txtusuario'], $_POST['cmbTipo'], $_POST['txtDescripcion'])) {
                    $result['exception'] = 'Faltan campos del formulario (id, nombre, sección o descripción)';
                    break;
                }

                if ($categorias->setIdcategoria($_POST['txtId'])) {
                    if ($data = $categorias->readOne()) {
                        if ($categorias->setCategoria($_POST['txtusuario'])) {
                            if ($categorias->setSeccion($_POST['cmbTipo'])) {
                                if ($categorias->setDescripcion($_POST['txtDescripcion'])) {
                                    // Verificar si hay archivo de imagen
                                    if (is_uploaded_file($_FILES['archivo_categoria']['tmp_name'])) {
                                        if ($categorias->setImagen($_FILES['archivo_categoria'])) {
                                            if ($categorias->updateRow($data['imagen'])) {
                                                $result['status'] = 1;
                                                // Guardamos la imagen dentro de la carpeta del proyecto
                                                if ($categorias->saveFile($_FILES['archivo_categoria'], $categorias->getRuta(), $categorias->getImagen())) {
                                                    $result['message'] = 'Categoría modificada correctamente';
                                                } else {
                                                    $result['message'] = 'Categoría modificada pero no se guardó la imagen';
                                                }
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = 'La imagen debe ser de al menos 500x500 píxeles';
                                        }
                                    } else {
                                        // Sin imagen nueva, solo actualizar datos
                                        if ($categorias->updateRow($data['imagen'])) {
                                            $result['status'] = 1;
                                            $result['message'] = 'Categoría modificada correctamente';
                                        } else {
                                            $result['exception'] = Database::getException();
                                        }
                                    }
                                } else {
                                    $result['exception'] = 'Descripción incorrecta';
                                }
                            } else {
                                $result['exception'] = 'Sección incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Categoría incorrecta';
                        }
                    } else {
                        $result['exception'] = 'Categoría inexistente';
                    }
                } else {
                    $result['exception'] = 'Id de categoría incorrecto';
                }
                break;
            // SI LA ACCION NO COINCIDE CON NINGUNO DE LOS CASOS MUESTRA ESTE MENSAJE
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
        header('content-type: application/json; charset=utf-8');
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
