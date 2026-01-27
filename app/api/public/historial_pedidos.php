<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/historial_pedidos.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    session_start();
    $categoria = new Pedidos;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se compara la acción a realizar según la petición del controlador.
    switch ($_GET['action']) {
        case 'readAll'://metodo para ver las facturas de un cliente
            if ($result['dataset'] = $categoria->cargarDatosParam($_SESSION['idcliente'])) {
                $result['status'] = 1;
            } else {
                if (Database::getException()) {
                    $result['exception'] = Database::getException();
                } else {
                    $result['exception'] = 'No existen pedidos para mostrar';
                }
            }
            break;
            // Metodo para cargar los datos de una factura y cargar su ID en la variable de sesion
            case 'readOne': 
                $_SESSION['idFactura'] = $_POST['txtId'];
                if ($_SESSION['idFactura'] != null) {       
                    $result['status'] = 1;
                } else {   
                    $result['exception'] = 'Factura inexistente';                  
                }
            break;
                $result['exception'] = 'Acción no disponible';
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
