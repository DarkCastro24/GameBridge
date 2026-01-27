<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/public_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Public_Page::headerTemplate('Iniciar sesión', 'public');
?>

<head>
    <link type="text/css" rel="stylesheet" href="../../resources/css/public_login.css" />
</head>

<body>
    <div class="row login">
        <div class="col s12 m12 l4 push-l3 offset-l1">
            <div class="card ">
                <div class="card-action white-text center-align">
                    <h4>Inicio de sesión </h4>
                    <img src="../../resources/img/brand/Logo.png" width="170" height="120">
                </div>
                <div class="card-content">
                    <form method="post" id="session-form">
                        <div class="form-field">
                            <label for="email">Correo electrónico
                            </label>
                            <input id="email" type="text" name="email" class="validate" autocomplete="off" required>
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
                        <div class="form-field center-align">
                                <a href="codigo.php" class="">¿Has olvidado tu contraseña?</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>


<?php
Public_Page::footerTemplate('login.js');
?>