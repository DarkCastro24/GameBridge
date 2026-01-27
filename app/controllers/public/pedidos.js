// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = '../../app/api/public/historial_pedidos.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que muestra las categorías disponibles.
    readAll();
});

// Función para obtener y mostrar las categorías existentes en la base.
function readAll() {
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readAll', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Declaramos atributos para almacenar datos
                    let content = '';
                    let subtotal = 0;
                    // Recorremos el contenido retornado en el dataset
                    response.dataset.map(function (row) {
                        // Calculamos el subtotal para mostrarlo en la tabla
                        subtotal = row.preciounitario * row.cantidad;
                        content += `
                        <tr>
                            <td>${row.direccion}</td>
                            <td>${row.fecha}</td>
                            <td>
                                <a href="#" onclick="openReport(${row.idfactura})" class="waves-effect waves-yellow updateButton btn updateButton tooltipped" data-tooltip="Comprobante de compra""><i class="material-icons left">assignment</i></a>
                            </td>
                        </tr>
                        `;
                    });                    
                    // Cargamos el contenido dentro de la cabecera de las tablas
                    document.getElementById('tbody-rows').innerHTML = content;
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    sweetAlert(4, 'No posee pedidos finalizados', 'index.php');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para obtener y mostrar las categorías existentes en la base.
function openReport(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('txtId', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            // Abrimos el reporte mediante su URL 
            window.open("../../app/reports/public/comprobante.php","");
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });

}

