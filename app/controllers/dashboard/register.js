// Constante para establecer la ruta y parámetros de comunicación con la API.
const API_USUARIOS = "../../app/api/dashboard/usuarios.php?action=";

// Método manejador de eventos que se ejecuta cuando el documento ha cargado.
document.addEventListener("DOMContentLoaded", function () {
  M.Tooltip.init(document.querySelectorAll(".tooltipped"));

  // Petición para verificar si existen usuarios.
  fetch(API_USUARIOS + "readAll", { method: "get" })
    .then(function (request) {
      if (request.ok) {
        request.json().then(function (response) {
          if (response.status) {
            sweetAlert(3, response.message, "index.php");
          } else {
            sweetAlert(4, "Debe crear un usuario para comenzar", null);
          }
        });
      } else {
        console.log(request.status + " " + request.statusText);
      }
    })
    .catch(function (error) {
      console.log(error);
    });
});

// Método manejador de eventos que se ejecuta cuando se envía el formulario de registrar.
document
  .getElementById("register-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    const correo = document.getElementById("correo").value.trim();
    const alias = document.getElementById("alias").value.trim();
    const clave1 = document.getElementById("clave1").value.trim();
    const clave2 = document.getElementById("clave2").value.trim();

    if (!correo || !alias || !clave1 || !clave2) {
      sweetAlert(2, "Complete todos los campos obligatorios.", null);
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo)) {
      sweetAlert(2, "Ingrese un correo válido.", null);
      return;
    }

    if (clave1.length < 8) {
      sweetAlert(2, "La clave debe tener al menos 8 caracteres.", null);
      return;
    }

    if (clave1 !== clave2) {
      sweetAlert(2, "Las claves no coinciden.", null);
      return;
    }

    fetch(API_USUARIOS + "register", {
      method: "post",
      body: new FormData(document.getElementById("register-form")),
    })
      .then(function (request) {
        if (request.ok) {
          request.json().then(function (response) {
            if (response.status) {
              sweetAlert(1, response.message, "index.php");
            } else {
              sweetAlert(2, response.exception, null);
            }
          });
        } else {
          console.log(request.status + " " + request.statusText);
        }
      })
      .catch(function (error) {
        console.log(error);
      });
  });
