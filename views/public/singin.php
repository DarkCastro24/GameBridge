<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Registrate', 'public');
?>

<div class="container" id="Registro">

    <h4 class="tamañoTitulos centrar">Si deseas adquirir nuestros productos <b>registrate</b></h4>
    <form method="post" id="register-form" autocomplete="off">
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" />
        <div class="row">
                <div class="input-field col s12 md6 lg6 xl6 ">
                    <input id="nombre" type="text" class="validate" autocomplete="off" name="nombre" pattern="[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]{1,50}" required>
                    <label for="nombre">Nombre</label>
                </div>
                <div class="input-field col s12 md6 lg6 xl6 ">
                    <input id="apellido" type="text" class="validate" autocomplete="off" name="apellido" pattern="[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]{1,50}" required>
                    <label for="apellido">Apellido</label>
                </div>
                <div class="input-field col s12 md6 lg6 xl6">
                    <input id="email" type="text" class="validate" name="email" autocomplete="off" maxlength="100" required>
                    <label for="email">Correo</label>
                </div>
                
            <div class="input-field col s12 md6 lg6 xl6">
                <input id="DUI" type="text" class="validate" name="DUI" autocomplete="off" placeholder="00000000-0"
                    pattern="[0-9]{8}[-][0-9]{1}" required>
                <label for="DUI">DUI</label>
                
            </div>
            <div class="input-field col s12 md6 lg6 xl6">
                <input id="clave1" type="password" class="validate" autocomplete="off" name="clave1" required>
                <label for="clave1">Contraseña</label>
            </div>
            <div class="input-field col s12 md6 lg6 xl6">
                <input id="clave2" type="password" class="validate" autocomplete="off" name="clave2" required>
                <label for="clave2">Confirmar contraseña</label>
            </div>
        </div>

        <div class="container" id="botonesRegistro">
            <div class="row center-align">

                <button class="btn botonRegistro" type="submit">Registrarse
                    <i class="material-icons right">person_add</i>
                </button>
            </div>

        </div>
    </form>

</div>
<script src="https://www.google.com/recaptcha/api.js?render=6Le5NEYcAAAAAOx8D6Cbrn1uKkqUwafs_s9ERza4"></script><?php
Public_Page::footerTemplate('singin.js');
?>