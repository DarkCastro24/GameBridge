// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = '../../app/api/public/clients.php?action=';

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', function () {
    // Manejador del formulario tradicional de login
    document.getElementById('session-form').addEventListener('submit', function (e) {
        e.preventDefault();
        iniciarSesion();
    });
});

// ============================================================
//  LOGIN TRADICIONAL (correo + contraseña)
// ============================================================
function iniciarSesion() {
    fetch(API_CLIENTES + 'logIn', {
        method: 'post',
        body: new FormData(document.getElementById('session-form'))
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
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
}

// ============================================================
//  LOGIN CON GOOGLE
//  Esta función es llamada automáticamente por el SDK de Google
//  cuando el usuario selecciona su cuenta exitosamente.
//  La función está definida en login.php en un script inline
// ============================================================
// handleGoogleLogin(googleResponse) - Ver el archivo login.php