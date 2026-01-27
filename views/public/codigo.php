<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Recuperar contraseña', 'public');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/public_login.css" />
</head>

<br>
<body>

    <div class="container">
    <div class="row login">
        <div class="col l5 push-l3 s12 m14 offset-s14 offset-l1">
            <div class="card ">
                <div class="card-action white-text center-align">
                    <h4>Recuperación por correo electrónico</h4>
                </div>
                <div class="card-content">
                    <form method="post" id="email-form">
                        <div class="form-field">
                            <label for="correo">Correo electrónico</label>
                            <input id="correo" type="text" name="correo" class="validate" autocomplete="off" required>
                        </div><br>
                        <div class="form-field">
                            <label for="codigo">Código</label>
                            <input id="codigo" type="number" name="codigo" class="validate" autocomplete="off" disabled required>
                        </div><br>
                    </form>
                    <div class="form-field center-align">
                        <button onclick="enviarCorreo()" class="button"><span>Enviar código</span></button>
                    </div> 
                    <div class="container white-text">
                        <center><br><a href="login.php">¿Desea regresar al login?</a><br><br></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

</body>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('codigo.js');
?>