<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Registrar primer usuario');
?>
<main class="container"> <!-- Abre contenedor para incluir el contenido de la pagina -->

    <head> <!-- Manda a llamar el css de la pagina -->
        <link type="text/css" rel="stylesheet" href="../../resources/css/styles.css" />
    </head> <br>
    <h3 class="center-align">Creación de primer usuario</h3>
    <!-- Formulario para registrar al primer usuario del dashboard -->
    <form method="post" id="register-form" novalidate>

        <!-- Tipo Root por defecto -->
        <input type="hidden" name="tipo" value="1">

        <div class="row">

            <!-- Correo -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">email</i>
                <input id="correo" type="email" name="correo"
                    placeholder="ejemplo@dominio.com"
                    required maxlength="60">
                <label for="correo">Correo *</label>
            </div>

            <!-- Usuario -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">person_pin</i>
                <input id="alias" type="text" name="alias"
                    placeholder="rootadmin"
                    required maxlength="35">
                <label for="alias">Usuario *</label>
            </div>

            <!-- Teléfono -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">phone</i>
                <input id="telefono" type="text" name="telefono"
                    placeholder="7123-4567"
                    required maxlength="9"
                    pattern="^[0-9]{8,9}$">
                <label for="telefono">Teléfono *</label>
            </div>

            <!-- DUI -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">credit_card</i>
                <input id="dui" type="text" name="dui"
                    placeholder="12345678-9"
                    required maxlength="10"
                    pattern="^[0-9]{8}-[0-9]{1}$">
                <label for="dui">DUI *</label>
            </div>

            <!-- Clave -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">security</i>
                <input id="clave1" type="password" name="clave1"
                    placeholder="Mínimo 8 caracteres"
                    required>
                <label for="clave1">Clave *</label>
            </div>

            <!-- Confirmar clave -->
            <div class="input-field col s12 m6">
                <i class="material-icons prefix">security</i>
                <input id="clave2" type="password" name="clave2"
                    placeholder="Repita la clave"
                    required>
                <label for="clave2">Confirmar clave *</label>
            </div>

        </div>

        <div class="row">
            <div class="col l7 m7 push-m5 push-l5 s7 push-s3">
                <button type="submit" class="button2">
                    <i class="material-icons">add</i>Crear usuario Root
                </button>
            </div>
        </div>

    </form>

    <?php
    // Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
    Dashboard_Page::footerTemplate('register.js');
    ?>