// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CATALOGO = '../../app/api/public/catalogo.php?action=';
const API_PEDIDOS = '../../app/api/public/pedidos.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    //Colocamos id = 1 porque 1 es la categoria de hardware en la base y servira para filtrar los productos
    document.getElementById('txtSeccion').value = 3; 
    // Se llama a la función que muestra las categorías disponibles.
    readAllCategorias();
});

// Metodo para cargar los datos mediante busqueda filtrada al presionar el boton
function busquedaFiltrada() {
    // Verificamos si el contenido del campo esta vacio
    if(document.getElementById("txtCategoria").value == '') {
        sweetAlert(2, 'Seleccione una categoria', null);
    }
    else {
        // Verificamos si el precio menor es mayor al precio mayor
        if(document.getElementById("search").value > document.getElementById("search2").value){
            sweetAlert(2, 'Precio menor mayor al precio mayor', null);
        }
        else {
            // Cargamos las cartas con el rango de precio indicado
            searchCards(API_CATALOGO, 'search-form');
        }
    }   
}

// Metodo para cargar todos los datos de la categoria seleccionada al presionar el boton
function cargarDatos() {
    // Verificamos si el contenido del campo esta vacio
    if(document.getElementById("txtCategoria").value == '') {
        sweetAlert(2, 'Seleccione una categoria', null);
    }
    else {
        // Obtenemos el tipo de categoria seleccionado 
        let categoria = document.getElementById("txtIdCategoria").value;
        // Cargamos los datos mediante la funcion read productos categorias
        readProductosCategoria(categoria);
        sweetAlert(1, 'Datos cargados correctamente', null);
    }   
}

// Función para obtener y mostrar las categorías existentes en la base.
function readAllCategorias() {
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readAllAccesorios', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se declara el atributo para guardar el contenido a cargar en el formulario
                    let content = '';
                    response.dataset.map(function (row) {
                        // Se carga la estructura de los botones en el menu lateral
                        content += `
                            <div class="col s12 m6 l12">
                                <a class="waves-effect btn" onclick="readProductosCategoria(${row.id})">${row.categoria}</a> 
                            </div>
                        `;
                    });              
                    // Ingresamos el contenido dentro de el contenedor con el id categorias      
                    document.getElementById('categorias').innerHTML = content;
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    document.getElementById('title').innerHTML = `<i class="material-icons small">cloud_off</i><span class="red-text">${response.exception}</span>`;
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readCards().
function fillCards(dataset) {
    // Se declara el atributo para guardar el contenido a cargar en el formulario
    let content = '';
    dataset.map(function (row) {
        // Se carga la estructura de las cartas que se mostraran dentro del form
        content += `
        <div class="col s4">
            <div class="card hoverable">
                <div class="card-image">
                    <img src="../../resources/img/productos/${row.imagen}" class="materialboxed">
                </div>
                <div class="card-content">
                    <span class="card-title">${row.producto}</span>
                    <p>Precio(US$) ${row.precio}</p><br>
                    <a onclick="openCreateDialog(${row.id})" class="waves-effect btn tooltipped boton" data-tooltip="Ver detalle">Mas informacion</a>
                    <br>
                </div>
            </div>
        </div>
        `;          
    });
    // Ingresamos el contenido dentro de el contenedor con el id productos
    document.getElementById('productos').innerHTML = content;
    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
}

// Función para obtener y mostrar los productos de acuerdo a la categoría seleccionada.
function readProductosCategoria(id) {
    // Se define un objeto con los datos del registro seleccionado.
    const data = new FormData();
    data.append('id_categoria', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readProductosCategoria', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
            request.json().then(function (response) {
                let idCategoria = '';
                if (response.status) {
                    let content = '';
                    // Se recorre el conjunto de registros devuelto por la API (dataset) fila por fila a través del objeto row.
                    response.dataset.map(function (row) {
                        document.getElementById('txtCategoria').value = row.cat; 
                        // Se crean y concatenan las tarjetas con los datos de cada producto.
                        content += `
                            <div class="col s12 m12 l4 xl4">
                                <div class="card hoverable">
                                    <div class="card-image">
                                        <img src="../../resources/img/productos/${row.imagen}" class="materialboxed">
                                    </div>
                                    <div class="card-content">
                                        <span class="card-title">${row.producto}</span>
                                        <p>Precio(US$) ${row.precio}</p><br>
                                        <a onclick="openCreateDialog(${row.id})" class="waves-effect waves-light btn tooltipped boton" data-tooltip="Ver detalle">Mas informacion</a>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        `;
                        idCategoria = row.idcategoria;
                    });
                    // Se agregan las tarjetas a la etiqueta div mediante su id para mostrar los productos.
                    document.getElementById('productos').innerHTML = content;
                    document.getElementById('txtIdCategoria').value = idCategoria; 
                    M.Materialbox.init(document.querySelectorAll('.materialboxed'));
                    // Se inicializa el componente Tooltip asignado a los enlaces para que funcionen las sugerencias textuales.
                    M.Tooltip.init(document.querySelectorAll('.tooltipped'));
                } else {
                    // Se presenta un mensaje de error cuando no existen datos para mostrar.
                    document.getElementById('txtCategoria').value = null; 
                    // Ejecutamos el metodo para eliminar el contenido de las cartas 
                    deleteCards();
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para borrar las cartas anteriores en caso de no existir productos de cierta categoria
function deleteCards() {
    let content = '';
    // Asignamos contenido vacio para que se elimine el contenido que hay dentro de la seccion de productos
    content += `
    `;
    // Imprimimos el contenido dentro del contenedor con id productos
    document.getElementById('productos').innerHTML = content;            
}

// Función para preparar el formulario al momento de insertar un registro.
function openCreateDialog(id) {
    // Reseteamos todos los valores del modal
    document.getElementById('save-form').reset();
    // Abrimos el modal
    let instance = M.Modal.getInstance(document.getElementById('save-modal'));
    instance.open();
    // Creamos un atributo para guardar el parametro de la funcion y enviarlo  al metodo ReadOne
    const data = new FormData();
    data.append('id', id);
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_CATALOGO + 'readOne', {
        method: 'post',
        body: data
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Creamos un atributo para guardar el contenido que se cargara en el formulario
                    let content = '';
                    // Asignamos la direccion de la imagen que se mostrara en la carta 
                    content += `
                        <img src="../../resources/img/productos/${response.dataset.imagen}" width="200px" height="150px" alt="Imagen-Producto">
                    `;
                    // Cargamos la imagen dentro del contenedor con id imagen
                    document.getElementById('imagen').innerHTML = content;
                    // Cargamos los datos del dataset dentro de los campos del modal 
                    document.getElementById('txtId').value = response.dataset.id; 
                    document.getElementById('modal-title').textContent = `Detalle del producto: ${response.dataset.producto}`;
                    document.getElementById('lblProducto').textContent = `Producto: ${response.dataset.producto}`;
                    document.getElementById('lblMarca').textContent = `Marca del producto: ${response.dataset.marca}`;
                    document.getElementById('lblDescripcion').textContent = `Descripcion del producto: ${response.dataset.descripcion}`;
                    document.getElementById('lblPrecio').textContent = `Precio del producto: ${response.dataset.precio}`;
                    document.getElementById('precio').value = response.dataset.precio; 
                    // Creamos un atributo para guardar el input de tipo number para la cantidad
                    let content2 = '';
                    // Asignamos la direccion de la imagen que se mostrara en la carta
                    content2 += `
                    <i class="material-icons prefix">list</i>
                    <input type="number" id="cantidad_producto" name="cantidad_producto" min="1" max="${response.dataset.cantidad-1}" class="validate" required/>
                    <label for="cantidad_producto">Cantidad</label>`;
                    document.getElementById('input-cantidad').innerHTML = content2;
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

// Método manejador de eventos que se ejecuta cuando se envía el formulario de agregar un producto al carrito.
document.getElementById('save-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API_PEDIDOS + 'createDetail', {
        method: 'post',
        // Enviamos como parametro el form que contiene los inputs 
        body: new FormData(document.getElementById('save-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se constata si el cliente ha iniciado sesión.
                if (response.status) {
                    sweetAlert(1, response.message, 'carrito.php');
                } else {
                    // Se verifica si el cliente ha iniciado sesión para mostrar la excepción, de lo contrario se direcciona para que se autentique. 
                    if (response.session) {
                        sweetAlert(2, response.exception, null);
                    } else {
                        sweetAlert(3, response.exception, 'login.php');
                    }
                }
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
});