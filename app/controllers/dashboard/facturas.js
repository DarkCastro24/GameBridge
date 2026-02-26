const API_FACTURAS = '../../app/api/dashboard/facturas.php?action=';
const ENDPOINT_VENDEDORES = '../../app/api/dashboard/vendedores.php?action=readAll';
const ENDPOINT_ESTADO = '../../app/api/dashboard/estados_factura.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_FACTURAS);
});

// Funcion para cargar los datos dentro de la tabla del formulario
function fillTable(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <tr>
               
                <td>${row.cliente}</td>
                <td>${row.estadofactura}</td>
                <td>
                    <a href="#" onclick="openUpdateDialog(${row.idfactura})" class="btn waves-effect btn updateButton tooltipped" data-tooltip="Actualizar"><i class="material-icons">update</i></a>
                    <a href="#" onclick="openAddressDialog(${row.idfactura})" class="waves-effect btn addressButton tooltipped" data-tooltip="Ver productos""><i class="material-icons left">visibility</i></a>

                </td>
            </tr>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows').innerHTML = content;
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_FACTURAS, 'search-form');
});

//Función para cargar los productos de una factura
function openAddressDialog(id) {
    getTotal(id);
    document.getElementById('save-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('address-modal'));
    instance.open();
    document.getElementById('txtIdx').value = id;
    readRows2(API_FACTURAS, 'address-form');
}

//Función para mostrar los productos por factura en una tabla
function fillTableParam(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `
            <tr>
                <td>${row.producto}</td>
                <td>${row.preciounitario}</td>
                <td>${row.cantidad}</td>
                <td>${row.totalunitario}</td>

            </tr>
        `;
    });

    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function DeleteTable() {
    let content = '';
    content += `
            <tr>

            </tr>
        `;
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

function openUpdateDialog(id) {
    // Se restauran los elementos del formulario.
    document.getElementById('save-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Se asigna el título para la caja de dialogo (modal).
    document.getElementById('modal-title').textContent = 'Modificar datos de la factura';
    // Se deshabilitan los campos de alias y contraseña.

    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('txtId', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_FACTURAS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    fillSelect(ENDPOINT_VENDEDORES, 'cmbTipo', response.dataset.vendedor);
                    fillSelect(ENDPOINT_ESTADO, 'cmbEstado', response.dataset.estado);
                    document.getElementById('txtId').value = response.dataset.idfactura;

                    // Se actualizan los campos para que las etiquetas (labels) no queden sobre los datos.
                    M.updateTextFields();
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

//Función para mostrar el total de una factura
function getTotal(id) {
    const data2 = new FormData();
    data2.append('txtIdx', id);

    fetch(API_FACTURAS + 'getTotal', {
        method: 'post',
        body: data2
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                let data = [];
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    document.getElementById('pago').textContent = response.dataset.total;
                } else {
                    //sweetAlert(4, response.exception, null);
                }
                // Se envían los datos a la función del controlador para que llene la tabla en la vista.
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}


// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = '';
    // Se comprueba si el campo oculto del formulario esta seteado para actualizar, de lo contrario será para crear.
    if (document.getElementById('txtId').value) {
        action = 'update';
    } else {
    }
    saveRow(API_FACTURAS, action, 'save-form', 'save-modal');
});