<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/catalogos.php');
require_once('../../models/categorias_producto.php');
require_once('../../models/productos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se instancian las clases correspondientes.
    $categoria = new Categorias_Producto;
    $producto = new Productos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null, 'dataset' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        // Metodo para cargar todos los productos
        case 'readAllHardware':
            if ($result['dataset'] = $categoria->readAllHardware()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen categorías para mostrar';
                }
            }
            break;
        // Metodo para cargar los periféricos
        case 'readAllPerifericos':
            if ($result['dataset'] = $categoria->readAllPerifericos()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen categorías para mostrar';
                }
            }
            break;
        // Metodo para cargar los accesorios
        case 'readAllAccesorios':
            if ($result['dataset'] = $categoria->readAllAccesorios()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen categorías para mostrar';
                }
            }
            break;
        // Metodo search para los productos
        case 'search':
            $_POST = $categoria->validateForm($_POST);
            if ($_POST['search'] != '') {
                if ($result['dataset'] = $producto->searchRows($_POST['search'])) {
                    $result['status'] = 1;
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
        // Metodo para cargar los productos por categoría
        case 'readProductosCategoria':
            if ($categoria->setIdcategoria($_POST['id_categoria'])) {
                if ($result['dataset'] = $categoria->readProductosCategoria()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen productos para mostrar';
                    }
                }
            } else {
                $result['exception'] = 'Categoría incorrecta';
            }
            break;
        // Metodo para cargar los datos del producto seleccionado
        case 'readOne':
            if ($producto->setId($_POST['id'])) {
                if ($result['dataset'] = $producto->readOne()) {
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

        // Método para obtener los TOP 4 productos más vendidos
        case 'getTopSold':
            if ($result['dataset'] = $producto->getTopSold()) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen productos para mostrar';
                }
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
