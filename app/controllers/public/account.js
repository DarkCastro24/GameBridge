// Constante para establecer la ruta y parámetros de comunicación con la API.
const API = '../../app/api/public/clients.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Se llama a la función que obtiene los registros para llenar la tabla. Se encuentra en el archivo components.js
    readRows3(API);
});

// Funcion para abrir el formulario para modificar el perfil del cliente
function openProfileDialog() {
    // Se abre la caja de dialogo (modal) que contiene el formulario para editar perfil, ubicado en el archivo de las plantillas.
    let instance = M.Modal.getInstance(document.getElementById('profile-modal'));
    instance.open();
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API + 'readProfile', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se inicializan los campos del formulario con los datos del usuario que ha iniciado sesión.
                    document.getElementById('correo_electronico').value = response.dataset.correo_electronico;
                    document.getElementById('nombres').value = response.dataset.nombres;
                    document.getElementById('apellidos').value = response.dataset.apellidos;
                    document.getElementById('dui').value = response.dataset.dui;
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

// Método manejador de eventos que se ejecuta cuando se envía el formulario de editar perfil.
document.getElementById('profile-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
    fetch(API + 'editProfile', {
        method: 'post',
        body: new FormData(document.getElementById('profile-form'))
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    // Se cierra la caja de dialogo (modal) del formulario.
                    let instance = M.Modal.getInstance(document.getElementById('profile-modal'));
                    instance.close();
                    // Se muestra un mensaje y se direcciona a la página web de bienvenida para actualizar el nombre del usuario en el menú.
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
});

// Función para mostrar el formulario de cambiar contraseña del usuario que ha iniciado sesión.
function openPasswordDialog() {
    // Se restauran los elementos del formulario.
    document.getElementById('password-form').reset();
    // Se abre la caja de dialogo (modal) que contiene el formulario para cambiar contraseña, ubicado en el archivo de las plantillas.
    let instance = M.Modal.getInstance(document.getElementById('password-modal'));
    instance.open();
}

// Método manejador de eventos que se ejecuta cuando se envía el formulario de cambiar clave.
document.getElementById('password-form').addEventListener('submit', function (event) {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();

    swal({
        title: 'Advertencia',
        text: '¿Está seguro de cambiar su clave?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de cerrar sesión, de lo contrario se muestra un mensaje.
        if (value) {
            fetch(API + 'changePassword', {
                method: 'post',
                body: new FormData(document.getElementById('password-form'))
            }).then(function (request) {
                // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                if (request.ok) {
                    request.json().then(function (response) {
                        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                        if (response.status) {
                            // Se cierra la caja de dialogo (modal) del formulario.
                            let instance = M.Modal.getInstance(document.getElementById('password-modal'));
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
        } else {
            sweetAlert(4, 'Puede continuar con la sesión', null);
        }
    });
    
});


// Función para mostrar un mensaje de confirmación al momento de cerrar sesión.
function logOut() {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro de cerrar la sesión?',
        icon: 'warning',
        buttons: ['No', 'Sí'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        // Se verifica si fue cliqueado el botón Sí para hacer la petición de cerrar sesión, de lo contrario se muestra un mensaje.
        if (value) {
            // Realizamos una peticion a la API indicando el caso a utilizar y enviando la direccion de la API como parametro
            fetch(API + 'logOut', {
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
            sweetAlert(4, 'Puede continuar con la sesión', null);
        }
    });
}

// Función para mostrar el formulario de cambiar contraseña del usuario que ha iniciado sesión.
function viewDevices() {
    // Se restauran los elementos del formulario.
    // Se abre la caja de dialogo (modal) que contiene el formulario para cambiar contraseña, ubicado en el archivo de las plantillas.
    let instance = M.Modal.getInstance(document.getElementById('device-modal'));
    instance.open();
}

function readRows3(api) {
    fetch(api + 'readDevices', {
        method: 'get'
    }).then(function (request) {
        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
        if (request.ok) {
            request.json().then(function (response) {
                let data = [];
                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                if (response.status) {
                    data = response.dataset;
                }
                // Se envían los datos a la función del controlador para que llene la tabla en la vista.
                fillTable4(data);
            });
        } else {
            console.log(request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log(error);
    });
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function fillTable4(dataset) {
    let content = '';
    // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
    dataset.map(function (row) {
        // Se crean y concatenan las filas de la tabla con los datos de cada registro.
        content += `
            <div class="col s12 m6 l6">

                <div class="card hoverable">

                
                    <div class="card-image waves-effect waves-block waves-light">
                    <img src="../../resources/img/device.png">
                    </div>

                        <div class="card-content">
                            <span class="grey-text text-darken-4">${row.sistema}</span><br><br>
                            <span onclick="openhistory('${row.sistema}')" class="card-title activator grey-text text-darken-4">Ver historial</span>

                            
                            
                        </div>
                   

                </div>



            </div>
        `;
    });
    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('devices').innerHTML = content;
}


function readRows4(api, form) {
    fetch(api + 'readAllParam', {
        method: 'post',
        body: new FormData(document.getElementById(form))
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    fillTableParam2(response.dataset);
                } else {
                    DeleteTable2();
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


// Función para preparar el formulario al momento de modificar un registro.
function openhistory(device) {
    // Se restauran los elementos del formulario.
    
    document.getElementById('txtIdX').value = device;
    let instance = M.Modal.getInstance(document.getElementById('history-modal'));
    instance.open();

    readRows4(API, 'history-form');



    
}


//Función para mostrar los productos por factura en una tabla
function fillTableParam2(dataset) {
    let content = '';
    dataset.map(function (row) {
        content += `

        <tr>
        <td>${row.hora.substring(0,10)}</td>
        <td>${row.hora.substring(11,16)}</td>

            </tr>

        

               

        `;
    });

    // Se agregan las filas al cuerpo de la tabla mediante su id para mostrar los registros.
    document.getElementById('historial').innerHTML = content;
 
}

// Función para llenar la tabla con los datos de los registros. Se manda a llamar en la función readRows().
function DeleteTable2() {
    let content = '';
    content += `
            
        `;
        document.getElementById('historial').innerHTML = content;
      
}
