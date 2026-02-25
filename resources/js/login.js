// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_CLIENTES = '../../app/api/public/clientes.php?action=';
const API_GOOGLE   = '../../app/api/public/google_callback.php';

// ============================================================
//  INICIALIZACIÓN DEL SDK DE GOOGLE
//  Se define ANTES de que el SDK cargue. El SDK con async defer
//  busca window.onGoogleLibraryLoad al terminar su carga.
//  Como login.js es síncrono y carga antes del SDK, esta función
//  ya estará registrada cuando el SDK la invoque.
// ============================================================
window.onGoogleLibraryLoad = function () {
    google.accounts.id.initialize({
        client_id: GOOGLE_CLIENT_ID_VALUE,  // inyectado por PHP en el HTML
        callback: handleGoogleLogin
    });
    google.accounts.id.renderButton(
        document.getElementById('google-signin-btn'),
        {
            type: 'standard',
            size: 'large',
            theme: 'outline',
            text: 'signin_with',
            shape: 'rectangular',
            logo_alignment: 'left',
            locale: 'es'
        }
    );
};

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
// ============================================================
function handleGoogleLogin(googleResponse) {
    // googleResponse.credential contiene el token JWT firmado por Google
    if (!googleResponse || !googleResponse.credential) {
        sweetAlert(2, 'No se recibió respuesta de Google', null);
        return;
    }

    // Preparamos el FormData con el token para enviarlo al backend
    const formData = new FormData();
    formData.append('credential', googleResponse.credential);

    fetch(API_GOOGLE, {
        method: 'POST',
        body: formData
    }).then(function (request) {
        if (request.ok) {
            request.json().then(function (response) {
                if (response.status) {
                    // Login exitoso → redirigir a la página principal del cliente
                    sweetAlert(1, response.message, 'index.php');
                } else {
                    sweetAlert(2, response.exception, null);
                }
            });
        } else {
            console.log('Error HTTP: ' + request.status + ' ' + request.statusText);
        }
    }).catch(function (error) {
        console.log('Error de red:', error);
        sweetAlert(2, 'Error de conexión al iniciar sesión con Google', null);
    });
}