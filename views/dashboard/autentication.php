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
        <div class="col l2"></div>
        <div class="col l8'">
            <div class="card ">
                <div class="card-action white-text center-align">
                    <h4>Autenticación de inicio de sesión Gamebridge </h4>
                    <img src="../../resources/img/brand/Logo.png" width="170" height="120">
                </div>
                <div class="card-content">
                    <form method="post" id="email-form">
                        <?php 
                        print'
                            <div class="form-field">
                                <label for="correo">Correo electrónico</label>
                                <input id="correo" type="text" name="correo" autocomplete="off" value="'.$_SESSION['correo']. '" class="validate white-text" required readonly />
                            </div><br>'
                        ?>
                        <div class="form-field">
                            <label for="codigo">Código de autenticación</label>
                            <input id="codigo" type="number" name="codigo" class="validate white-text" required autocomplete="off"> 
                        </div><br>
                        <div class="container white-text">
                            <center><br><a onclick="logOut2()">¿Deseas regresar al login?</a><br><br></center>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col l7 m7 push-m5 push-l4 s7 push-s3">
                            <button onclick="verificarCodigo()" class="button3">Verificar código</button>
                        </div>
                    </div>               
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('autentication.js');
?>