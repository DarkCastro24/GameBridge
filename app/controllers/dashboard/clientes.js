// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PRODUCTOS = '../../app/api/dashboard/clientes.php?action=';
const ENDPOINT_CATEGORIAS = '../../app/api/dashboard/categorias.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    readRows(API_PRODUCTOS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `
            <tr>
                <td>${row.estado}</td>
                <td>${row.nombres}</td>
                <td>${row.apellidos}</td>
                <td>${row.dui}</td>
                <td>${row.correo_electronico}</td>
                <td>
                    <a href="#" onclick="openDeleteDialog(${row.id})" class="waves-effect waves btn deleteButton tooltipped" data-tooltip="Eliminar""><i class="material-icons left">delete</i></a>
                    <a href="#" onclick="openUpdateDialog(${row.id})" class="waves-effect waves-yellow btn updateButton tooltipped" data-tooltip="Actualizar""><i class="material-icons left">update</i></a>
                    <a href="#" onclick="openAddressDialog(${row.id})" class="waves-effect waves-yellow btn addressButton tooltipped" data-tooltip="Ver direcciones""><i class="material-icons left">visibility</i></a>
                </td>
            </tr>
        `;          
    });
    document.getElementById('tbody-rows').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de buscar.
document.getElementById('search-form').addEventListener('submit', function (event) {
    event.preventDefault();
    searchRows(API_PRODUCTOS, 'search-form');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreateDialog() {
    document.getElementById('save-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    document.getElementById('modal-title').textContent = 'Agregar cliente';    
    fillSelect(ENDPOINT_CATEGORIAS, 'cmbEstado', null);
}


// Función para preparar el formulario al momento de mostrar las direcciones de un cliente.
function openAddressDialog(id) {
    document.getElementById('save-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('address-modal'));
    instance.open();    
    document.getElementById('txtIdx').value = id;
    readRows2(API_PRODUCTOS,'address-form');
}


// Función para preparar el formulario al momento de modificar un registro.
function openUpdateDialog(id) {
    // Reteamos el valor de los inputs del modal
    document.getElementById('save-form').reset();
    // Abrimos el modal 
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Colocamos el titulo al modal
    document.getElementById('modal-title').textContent = 'Modificar cliente';
    const data = new FormData();
    data.append('id', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_PRODUCTOS + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    document.getElementById('txtId').value = response.dataset.id;
                    document.getElementById('txtNombre').value = response.dataset.nombres;
                    document.getElementById('txtApellido').value = response.dataset.apellidos;
                    document.getElementById('txtDui').value = response.dataset.dui;
                    document.getElementById('txtCorreo').value = response.dataset.correo;
                    document.getElementById('txtClave').value = response.dataset.clave;
                    document.getElementById('txtClave2').value = response.dataset.clave;
                    fillSelect(ENDPOINT_CATEGORIAS, 'cmbEstado', response.dataset.estado);
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

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('save-form').addEventListener('submit', function (event) {
    event.preventDefault();
    let action = '';
    if (document.getElementById('txtId').value) {
        action = 'update';
    } else {
        action = 'create';
    }
    saveRow(API_PRODUCTOS, action, 'save-form', 'save-modal');
});

// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
function openDeleteDialog(id) {
    const data = new FormData();
    data.append('id', id);
    confirmDelete(API_PRODUCTOS, data);
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTableParam(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `
            <tr>
                <td>${row.direccion}</td>
                <td>${row.codigo}</td>
                <td>${row.telefono}</td>
            </tr>
        `;          
    });
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
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