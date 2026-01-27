// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = '../../app/api/dashboard/usuarios.php?action=';

// Metodo para ocultar y mostrar la contraseña
function mostrarClave() {
    let tipo = document.getElementById("clave");
    let icon = '';
    let content = '';
    if(tipo.type == "password") {
        tipo.type = "text";
        icon = 'visibility_off';
    } else {
        tipo.type = "password";
        icon = 'visibility';
    }
    content += `
        <br><br><a onclick="mostrarClave()"><i class="material-icons">${icon}</i></a>
    `;
    document.getElementById('xd').innerHTML = content;
}

// Metodo para cargar todos los datos de la categoria seleccionada al presionar el boton
function cambiarClave() {  
    // Validamos que el campo de usuario no este vacio
    if(document.getElementById("clave1").value == '') {
        sweetAlert(3, 'Ingrese su nueva contraseña', null);
    } else {
        // Validamos que el campo de clave no este vacio
        if(document.getElementById("clave2").value == '') {
            sweetAlert(3, 'Ingrese la confirmación de la contraseña', null);
        } else {
            if (document.getElementById("clave1").value != document.getElementById("clave2").value) {
                sweetAlert(3, 'Las claves ingresadas deben ser iguales', null);
            } else {
                if (document.getElementById("clave1").value != document.getElementById("clave").value) {
                    // Realizamos peticion a la API de clientes con el caso changePass y method post para dar acceso al valor de los campos del form
                    fetch(API_USUARIOS + 'changePass', {
                        method: 'post',
                        body: new FormData(document.getElementById('session-form'))       
                    }).then(function (request) {
                        // Se verifica si la petición es correcta, de lo contrario se muestra un mensaje indicando el problema.
                        if (request.ok) {
                            request.json().then(function (response) {
                                // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
                                if (response.status) {
                                    // En caso de iniciar sesion correctamente mostrar mensaje y redirigir al menu
                                    sweetAlert(1, response.message, 'main.php');
                                } else {
                                    sweetAlert(3, response.exception, null);
                                }
                            });
                        } else {
                            console.log(request.status + ' ' + request.statusText);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else {
                    sweetAlert(4, 'La nueva clave no puede ser igual a la clave anterior', null);
                }
            }
            
        }
    }   
}

