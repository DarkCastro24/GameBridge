// Constantes para establecer las rutas y parámetros de comunicación con la API.
const API_PRODUCTOS = '../../app/api/dashboard/productos.php?action=';
const ENDPOINT_CATEGORIAS = '../../app/api/dashboard/categoria_producto.php?action=readAll';
const ENDPOINT_ESTADO = '../../app/api/dashboard/estado_producto.php?action=readAll';
const ENDPOINT_MARCA = '../../app/api/dashboard/marca_producto.php?action=readAll';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows(API_PRODUCTOS);
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `
            <tr>
                <td><img src="../../resources/img/productos/${row.imagen}" class="materialboxed" height="100"></td>
                <td>${row.categoria}</td>
                <td>${row.estado}</td>
                <td>${row.marca}</td>
                <td>${row.producto}</td>
                <td>${row.precio}</td>
                <td>${row.cantidad}</td>
                <td>
                    <a href="#" onclick="openUpdateDialog(${row.id})" class="btn waves-effect blue tooltipped" data-tooltip="Actualizar"><i class="material-icons">mode_edit</i></a>
                    <a href="#" onclick="openDeleteDialog(${row.id})" class="btn waves-effect red tooltipped" data-tooltip="Eliminar"><i class="material-icons">delete</i></a>
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
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se llama a la función que realiza la búsqueda. Se encuentra en el archivo components.js
    searchRows(API_PRODUCTOS, 'search-form');
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreateDialog() {
    // Eliminamos el contenido de todos los elementos dentro de save form
    document.getElementById('save-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    // Abrimos el modal
    instance.open();
    // Colocamos el titulo al modal 
    document.getElementById('modal-title').textContent = 'Registrar producto';
    document.getElementById('archivo_producto').required = true;
    // Cargamos los combobox 
    fillSelect(ENDPOINT_ESTADO, 'cmbEstado', null);
    fillSelect(ENDPOINT_CATEGORIAS, 'cmbCategoria', null);
    fillSelect(ENDPOINT_MARCA, 'cmbMarca', null);
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdateDialog(id) {
    // Reseteamos los valores de los inputs del modal
    document.getElementById('save-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Colocamos titulo al modal
    document.getElementById('modal-title').textContent = 'Actualizar producto';
    document.getElementById('archivo_producto').required = false;
    // Creamos un form data para enviar el id 
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
                    // Cargamos los datos en los inputs del modal
                    document.getElementById('txtId').value = response.dataset.id;
                    document.getElementById('txtProducto').value = response.dataset.producto;
                    document.getElementById('txtPrecio').value = response.dataset.precio;
                    document.getElementById('txtDescripcion').value = response.dataset.descripcion;
                    // Cargamos el contenido de los select (combobox)
                    fillSelect(ENDPOINT_ESTADO, 'cmbEstado', response.dataset.estado);
                    fillSelect(ENDPOINT_CATEGORIAS, 'cmbCategoria', response.dataset.categoria);
                    fillSelect(ENDPOINT_MARCA, 'cmbMarca', response.dataset.marca);
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

// Controlador del evento click del boton para guardar o actualizar datos
document.getElementById('save-form').addEventListener('submit', function (event) {
    event.preventDefault();
    let action = '';
    // Comparamos si existe o no id 
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
    data.append('txtId', id);
    // Ejecutamos la funcion para eliminar un producto
    confirmDelete(API_PRODUCTOS, data);
}