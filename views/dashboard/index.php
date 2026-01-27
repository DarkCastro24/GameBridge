<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Iniciar sesión');
?>
<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/login_styles.css" />
</head>
<body>
    <div class="row login">
        <div class="col s12 m14 offset-s14">
            <div class="card ">
                <div class="card-action white-text center-align">
                    <h4>Inicio de sesión </h4>
                    <img src="../../resources/img/brand/Logo.png" width="170" height="120">
                </div>
                <div class="card-content">
                    <form method="post" id="session-form">
                        <div class="form-field">
                            <label for="alias">Usuario</label>
                            <input id="alias" type="text" name="alias" class="validate" autocomplete="off" required>
                        </div><br>
                        <div class="form-field">
                            <label for="clave">Contraseña</label>
                            <input id="clave" type="password" name="clave" class="validate" autocomplete="off" required>
                        </div><br>
                        <div class="form-field center-align">
                            <a><button type="submit" class="button"><span>Ingresar</span>
                                </button>
                            </a>
                        </div> 
                        <div class="container white-text">
                            <br><a href="codigo.php">¿Has olvidado tu contraseña?</a><br><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('index.js');
?>