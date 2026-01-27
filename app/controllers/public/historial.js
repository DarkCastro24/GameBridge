// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = '../../app/api/public/historial.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que muestra las categorías disponibles.
    readAll();
});

// Función para preparar el formulario al momento de insertar un registro.
function openCreateDialog(id,producto) {
    document.getElementById('save-form').reset();
    // Se deshabilitan los campos de alias y contraseña.
    document.getElementById('txtProducto').disabled = false;
    document.getElementById('txtId').value = id;
    document.getElementById('txtProducto').value = producto;
    // Abrimos el modal 
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    document.getElementById('modal-title').textContent = 'Agregar reseña de un producto';    
}

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
                    // Declaramos atributos para guardar datos
                    let content = '';
                    let subtotal = 0;
                    // Recorremos el contenido retornado en el dataset
                    response.dataset.map(function (row) {
                        // Calculamos el valor del subtotal para mostrarlo en la tabla
                        subtotal = row.preciounitario * row.cantidad;
                        // Asignamos la direccion de la imagen que se mostrara en la carta 
                        content += `
                        <tr>
                            <td><img src="../../resources/img/productos/${row.imagen}" class="materialboxed" width="90px" height="90px"></td>
                            <td>${row.producto}</td>
                            <td>${row.preciounitario}</td>
                            <td>${row.cantidad}</td>
                            <td>${subtotal}</td>
                            <td>
                                <a href="#" onclick="openCreateDialog(${row.id},'${row.producto}')" class="waves-effect waves btn updateButton tooltipped" data-tooltip="Agregar""><i class="material-icons left">add</i></a>
                                <a href="#" onclick="openUpdateDialog(${row.id})" class="waves-effect waves btn updateButton tooltipped" data-tooltip="Actualizar""><i class="material-icons left">update</i></a>
                                <a href="#" onclick="openDeleteDialog(${row.id})" class="waves-effect waves btn deleteButton tooltipped" data-tooltip="Eliminar""><i class="material-icons left">delete</i></a>
                            </td>
                        </tr>
                        `;
                    });                    
                    document.getElementById('tbody-rows').innerHTML = content;
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    sweetAlert(4, 'No existen productos en su historial', 'index.php');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para preparar el formulario al momento de modificar un registro.
function openUpdateDialog(id) {
    // Limpiamos los campos del formulario
    document.getElementById('update-form').reset();
    // Abrimos el formulario
    let instance = M.Modal.getInstance(document.getElementById('update-modal'));
    instance.open();
    // Colocamos el titulo al modal
    document.getElementById('modal-title2').textContent = 'Actualizar reseña de producto';
    document.getElementById('txtIdx').value = id;
    // Se deshabilitan los campos.
    document.getElementById('txtProducto').disabled = true;
    const data = new FormData();
    data.append('txtIdX', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Cargamos los datos en los inputs
                    document.getElementById('txtProducto2').value = response.dataset.producto;
                    document.getElementById('txtCalificacion2').value = response.dataset.calificacion_producto;
                    document.getElementById('txtComentario2').value = response.dataset.comentario_producto;
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
    let action = 'create';
    // Mandamos a llamar la funcion para guardar datos y sin actualizar la tabla
    onlySaveRow(API_CATALOGO, action, 'save-form', 'save-modal');
});

// Método manejador de eventos que se ejecuta cuando se envía el formulario de actualizar
document.getElementById('update-form').addEventListener('submit', function (event) {
    event.preventDefault();
    let action = 'update';
    // Mandamos a llamar la funcion para guardar datos y sin actualizar la tabla
    onlySaveRow(API_CATALOGO, action, 'update-form', 'update-modal');
});

// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
function openDeleteDialog(id) {
    const data = new FormData();
    data.append('txtId', id);
    // Mandamos a llamar el metodo para eliminar un registro
    confirmDelete(API_CATALOGO, data);
}
