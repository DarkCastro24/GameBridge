<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Cambio de clave', 'public');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/public_login.css" />
</head>

<body>
    <div class="row login">
        <div class="col l3"></div>
        <div class="col l6">
            <div class="card ">
                <div class="card-action white-text center-align">
                    <h4>Autenticación de inicio de sesión </h4>
                    <img src="../../resources/img/brand/Logo.png" width="170" height="120">
                </div>
                <div class="card-content">
                    <form method="post" id="email-form">
                        <?php
                        print '
                            <div class="form-field">
                                <label for="correo">Correo electrónico</label>
                                <input id="correo" type="text" name="correo" autocomplete="off" value="' . $_SESSION['correo_electronico'] . '" class="validate white-text" required disabled />
                            </div><br>'
                        ?>
                        <div class="form-field">
                            <label for="codigo">Código de autenticación</label>
                            <input id="codigo" type="number" autocomplete="off" name="codigo" class="validate white-text" required>
                        </div><br>
                    </form>
                    <div class="row">
                        <div class="form-field center-align">
                        <button onclick="verificarCodigo()" class="button">Verificar código</button>

                        </div><br>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('autentication.js');
?>