// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_PEDIDOS = '../../app/api/public/pedidos.php?action=';
const API_DIRECCIONES = '../../app/api/public/direcciones.php?action=';
const ENDPOINT_CATEGORIAS = '../../app/api/public/direcciones_cliente.php?action=readAll';

// Función para preparar el formulario al momento de mostrar las direcciones de un cliente.
function openAddressDialog() {
    // Reseteamos todos los inputs del formulario
    document.getElementById('address-form').reset();
    let instance = M.Modal.getInstance(document.getElementById('address-modal'));
    instance.open();
    // Mandamos a llamar la funcion para cargar los datos de las direcciones
    readRows2(API_PEDIDOS, 'address-form');
}

// Función para establecer el registro a eliminar y abrir una caja de dialogo de confirmación.
function openDeleteDialog2(id) {
    const data = new FormData();
    data.append('txtIdx', id);
    // Mandamos a llamar la funcion para eliminar un pedido
    confirmDelete(API_PEDIDOS, data);
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTableParam(dataset) {
    // Declaramos un atributo para guardar el contenido
    let content = '';
    dataset.map(function (row) {
        // Se carga la estructura de la tabla que se mostraran dentro del form
        content += `
            <tr>
                <td>${row.direccion}</td>
                <td>${row.codigo_postal}</td>
                <td>${row.telefono_fijo}</td>
                <td><a href="#" onclick="openDeleteDialog2(${row.iddireccion})" class="waves-effect waves btn deleteButton tooltipped" data-tooltip="Eliminar""><i class="material-icons left">delete</i></a></td>
            </tr>
        `;
    });
    // Ingresamos el contenido dentro del body de la tabla 
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function DeleteTable() {
    // Declaramos un atributo para guardar el contenido
    let content = '';
    // Declaramos el contenido vacio
    content += `
        <tr></tr>
    `;
    // Ingresamos el contenido dentro del body de la tabla 
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los productos del carrito de compras para llenar la tabla en la vista.
    readOrderDetail();
});

// Función para obtener el detalle del pedido (carrito de compras).
function readOrderDetail() {
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_PEDIDOS + 'readOrderDetail', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se declara e inicializa una variable para concatenar las filas de la tabla en la vista.
                    let content = '';
                    // Se declara e inicializa una variable para calcular el importe por cada producto.
                    let subtotal = 0;
                    // Se declara e inicializa una variable para ir sumando cada subtotal y obtener el monto final a pagar.
                    let total = 0;
                    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        // Calculamos el subtotal para mostrarlo en la tabla
                        subtotal = row.preciounitario * row.cantidad;
                        total += subtotal;
                        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
                        content += `
                            <tr>
                                <td>${row.producto}</td>
                                <td>${row.cantidad}</td>
                                <td>${row.preciounitario}</td>
                                <td>${subtotal.toFixed(2)}</td>
                                <td><img src="../../resources/img/productos/${row.imagen}" class="materialboxed" height="70"></td>

                                <td>
                                    <a href="#" onclick="actualizarCantidad(${row.iddetallefactura}, ${row.cantidad}, ${row.idproducto})" class="btn waves-effect updateButton tooltipped" data-tooltip="Cambiar"><i class="material-icons">exposure</i></a>
                                    <a href="#" onclick="openDeleteDialog(${row.iddetallefactura},${row.cantidad}, ${row.idproducto})" class="btn waves-effect deleteButton tooltipped" data-tooltip="Remover"><i class="material-icons">remove_shopping_cart</i></a>
                                </td>
                            </tr>
                        `;


                    });
                    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
                    document.getElementById('tbody-rows').innerHTML = content;
                    // Se muestra el total a pagar con dos decimales.
                    document.getElementById('pago').textContent = total.toFixed(2);
                    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    sweetAlert(4, response.exception, 'index.php');
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

//funcion para actualizar la cantidad del producto
function actualizarCantidad(iddetalle, cantidadActual, producto) {

    let instance = M.Modal.getInstance(document.getElementById('item-modal'));
    instance.open();
    //Seteando valores necesarios para hacer la actualización
    document.getElementById('lblCantidadMaterial').textContent = cantidadActual;
    document.getElementById('id_detalle').value = iddetalle;
    document.getElementById('idproducto').value = producto;
    document.getElementById('cantidad_producto').value = cantidadActual;
    const data = new FormData();
    data.append('idproducto', producto);

    fetch(API_PEDIDOS + 'readOneMaterial', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    document.getElementById("cantidad_producto").max = parseInt(response.dataset.cantidad - 1);;
                    var cantidadDetalle = parseInt(document.getElementById('lblCantidadMaterial').textContent);
                    var stockDisponible = parseInt(response.dataset.cantidad);
                    var verdaderoStock = cantidadDetalle + stockDisponible;
                    document.getElementById('stockReal').value = verdaderoStock;
                    document.getElementById('txtCantidad2').value = verdaderoStock;
                    cantidad = verdaderoStock;
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
    M.updateTextFields();

}

document.getElementById('item-form').addEventListener('submit', function (event) {
    event.preventDefault();

    /*Calculos para determinar el stock que estará en bodega y en el pedido*/
    var stockPedido = parseInt(document.getElementById('cantidad_producto').value);
    var stockActual = parseInt(document.getElementById('stockReal').value);
    var stockNuevo = stockActual - stockPedido;
    document.getElementById('stockPedido').value = stockPedido;
    document.getElementById('stockBodega').value = stockNuevo;

    //Petición
    fetch(API_PEDIDOS + 'updateStock', {
        method: 'post',
        body: new FormData(document.getElementById('item-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del registro seleccionado.
                    readOrderDetail();
                    // Se cierra la caja de dialogo (modal) del formulario.
                    let instance = M.Modal.getInstance(document.getElementById('item-modal'));
                    instance.close();
                    sweetAlert(1, response.message, null);

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
})
// Función para mostrar un mensaje de confirmación al momento de finalizar el pedido.
function finishOrder() {
    swal({
        title: 'Aviso',
        text: '¿Está seguro de finalizar el pedido?',
        icon: 'info',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para realizar la petición respectiva, de lo contrario se muestra un mensaje.
        if (value) {
            fetch(API_PEDIDOS + 'finishOrder', {
                method: 'get'
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            sweetAlert(1, response.message, 'index.php');
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
        } else {
            sweetAlert(4, 'Puede seguir comprando', null);
        }
    });
}

// Función para mostrar un mensaje de confirmación al momento de eliminar un producto del carrito.
function openDeleteDialog(id, cantidad, producto) {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro de remover el producto?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para realizar la petición respectiva, de lo contrario no se hace nada.
        if (value) {
            // Se define un objeto con los datos del registro seleccionado.
            const data = new FormData();
            data.append('id_detalle', id);
            data.append('cantidad_producto', cantidad);
            data.append('idproducto', producto);

            fetch(API_PEDIDOS + 'deleteDetail', {
                method: 'post',
                body: data
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Se cargan nuevamente las filas en la tabla de la vista después de borrar un producto del carrito.
                            readOrderDetail();
                            sweetAlert(1, response.message, null);
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
    });
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateDialog() {
    // Se restauran los elementos del formulario.
    document.getElementById('address-form2').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de guardar.
document.getElementById('address-form2').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se define una variable para establecer la acción a realizar en la API.
    let action = 'create';
    saveRow(API_PEDIDOS, action, 'address-form2', 'save-modal');
});


function openFixDialog() {
    // Se restauran los elementos del formulario.
    document.getElementById('fix-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario.
    let instance = M.Modal.getInstance(document.getElementById('change-modal'));
    instance.open();
    fillSelect(ENDPOINT_CATEGORIAS, 'cmbDireccion', null);
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de fijar la direccion de envio.
document.getElementById('fix-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_DIRECCIONES + 'update', {
        method: 'post',
        body: new FormData(document.getElementById('fix-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    sweetAlert(1, response.message, 'carrito.php');
                } else {
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable(dataset) {
    // Declaramos un atrubuto para guardar el contenido a mostrar
    let content = '';
    // Recorremos el arreglo del dataset
    dataset.map(function (row) {
        // Cargamos el contenido que mostraremos en la tabla
        content += `
            <tr>
                <td>${row.iddireccion}</td>
                <td>${row.direccion}</td>
                <td>${row.codigo_postal}</td>
                <td>${row.telefono_fijo}</td>
                <td><a href="#" onclick="openDeleteDialog2(${row.iddireccion})" class="waves-effect waves btn deleteButton tooltipped" data-tooltip="Eliminar""><i class="material-icons left">delete</i></a></td>
            </tr>
        `;
    });
    // Cargamos los datos obtenidos en el cuerpo de la tabla 
    document.getElementById('tbody-rows2').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}




