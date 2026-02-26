<?php
/*
*	Clase para definir las plantillas de las páginas web del sitio privado.
*/
class Dashboard_Page
{
    /*
    *   Método para imprimir la plantilla del encabezado.
    *
    *   Parámetros: $title (título de la página web y del contenido principal).
    *
    *   Retorno: ninguno.
    */
    public static function headerTemplate($title)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        // Se imprime el código HTML de la cabecera del documento.
        print('       
        <!DOCTYPE html>
            <html lang="es">
              <head>
                <meta charset="utf-8"> 
                <!--Se importan los archivos CSS locales-->
                <link type="text/css" rel="stylesheet" href="../../resources/css/materialize.min.css" />
                <link type="text/css" rel="stylesheet" href="../../resources/css/dashboard_styles.css" />
                <link type="text/css" rel="stylesheet" href="../../resources/css/material_icons.css" />
                <link rel="icon" type="image/png" href="../../resources/img/brand/Logo.png" />
                <!--Se informa al navegador que el sitio web está optimizado para dispositivos móviles-->
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <!--Título del documento-->
                <title>Dashboard | ' . $title . '</title>
              </head>
            <body>
            <style>
                .nav{
                    background-color: rgb(10, 23, 51);
                }

                .centrar{
                    text-align:center;
                }
        
                .footer{
                    background-color: rgb(10, 23, 51);
                }
            </style>
        ');
        // Se obtiene el nombre del archivo de la página web actual.
        $filename = basename($_SERVER['PHP_SELF']);
        // Se comprueba si existe una sesión de administrador para mostrar el menú de opciones, de lo contrario se muestra un menú vacío.
        if (isset($_SESSION['idusuario'])) {
            // Se verifica si la página web actual es diferente a index.php (Iniciar sesión) y a register.php (Crear primer usuario) para no iniciar sesión otra vez, de lo contrario se direcciona a main.php
            if ($filename != 'index.php' && $filename != 'register.php' && $filename != 'password.php' && $filename != 'codigo.php' && $filename != 'clave.php') {
                // Se llama al método que contiene el código de las cajas de dialogo (modals).
                self::modals();
                // Se compara el tipo de usuario que ha iniciado sesion en el sistema                
                if ($_SESSION['tipo'] == 1) {
                    // Se imprime el código HTML para el encabezado del documento con el menú de opciones.
                    print('
                        <!--Encabezado del documento-->
                        <header>
                            <!--Barra de navegación fija-->
                            <div class="navbar-fixed">
                                <nav class="nav">
                                    <div class="nav-wrapper">
                                        <a href="main.php" class="brand-logo"><img src="../../resources/img/brand/Logo.png" width="80" height="60.3"></a>
                                        <span class="white-text hide-on-med-and-down" style="margin-left: 84px; line-height: 64px; font-weight: bold; font-size: 18px;">Gamebridge Dashboard</span>
                                        <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                        <ul class="right hide-on-med-and-down">
                                            <li><a href="usuarios.php"><i class="material-icons right">account_circle</i>Usuarios</a></li>
                                            <li><a href="products.php"><i class="material-icons right">shopping_cart</i>Productos</a></li>
                                            <li><a href="categorias.php"><i class="material-icons right">category</i>Categorías</a></li>
                                            <li><a href="clients.php"><i class="material-icons right">supervisor_account</i>Clientes</a></li>
                                            <li><a href="facturas.php"><i class="material-icons right">receipt</i>Facturas</a></li>
                                            <li><a href="valoraciones.php"><i class="material-icons right">chat_bubble_outline
                                            </i>Valoraciones</a></li>
                                            <li><a href="#" class="dropdown-trigger" data-target="dropdown"><i class="material-icons left">supervisor_account</i>Cuenta: <b>' . $_SESSION['usuario'] . '</b></a></li>
                                            </ul>
                                            <ul id="dropdown" class="dropdown-content">
                                                <li><a class ="black-text">Tipo: Root</a></li>
                                                <li><a href="#" onclick="openProfileDialog()" class = "black-text"><i class="material-icons">face</i>Editar perfil</a></li>
                                                <li><a href="#" onclick="openPasswordDialog()"class = "black-text"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                                <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                                                <li><a href="#" onclick="logOut()"class = "black-text"><i class="material-icons">clear</i>Salir</a></li>
                                            </ul>
                                    </div>
                                </nav>
                            </div>
                            <!--Navegación lateral para dispositivos móviles-->
                            <ul class="sidenav" id="mobile-sidenav">
                                <li><a href="usuarios.php"><i class="material-icons right">account_circle</i>Usuarios</a></li>
                                <li><a href="products.php"><i class="material-icons right">shopping_cart</i>Productos</a></li>
                                <li><a href="clients.php"><i class="material-icons right">supervisor_account</i>Clientes</a></li>
                                <li><a href="facturas.php"><i class="material-icons right">receipt</i>Facturas</a></li>
                                <li><a href="valoraciones.php"><i class="material-icons right">chat_bubble_outline</i>Valoraciones</a></li>
                                <li><a href="#" class="dropdown-trigger" data-target="dropdown-mobile"><i class="material-icons left">supervisor_account</i>Cuenta: <b>' . $_SESSION['usuario'] . '</b></a></li>
                                </ul>

                                <ul id="dropdown-mobile" class="dropdown-content dropdown">
                                <li><a href="#" onclick="openProfileDialog()"><i class="material-icons">face</i>Editar perfil</a></li>
                                <li><a href="#" onclick="openPasswordDialog()"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                                <li><a href="#" onclick="logOut()"><i class="material-icons">clear</i>Salir</a></li>
                            </ul>
                        </header>
                        <!--Contenido principal del documento-->
                        <main>
                        ');
                } else {
                    // Se imprime el código HTML para el encabezado del documento con el menú de opciones.
                    if ($filename != 'clave.php') {
                        print('
                        <!--Encabezado del documento-->
                        <header>
                            <!--Barra de navegación fija-->
                            <div class="navbar-fixed">
                                <nav class="nav">
                                    <div class="nav-wrapper">
                                        <a href="main.php" class="brand-logo"><img src="../../resources/img/brand/Logo.png" width="80" height="60.3"></a>
                                        <span class="white-text hide-on-med-and-down" style="margin-left: 84px; line-height: 64px; font-weight: bold; font-size: 18px;">Gamebridge Dashboard</span>
                                        <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                        <ul class="right hide-on-med-and-down">
                                            <li><a href="products.php"><i class="material-icons right">shopping_cart</i>Productos</a></li>
                                            <li><a href="categorias.php"><i class="material-icons right">category</i>Categorías</a></li>
                                            <li><a href="clients.php"><i class="material-icons right">supervisor_account</i>Clientes</a></li>
                                            <li><a href="#" class="dropdown-trigger" data-target="dropdown"><i class="material-icons left">account_circle</i>Cuenta: <b>' . $_SESSION['usuario'] . '</b></a></li>
                                            </ul>
                                            <ul id="dropdown" class="dropdown-content">
                                                <li><a class ="black-text">Tipo: Admin</a></li>
                                                <li><a href="#" onclick="openProfileDialog()" class = "black-text"><i class="material-icons">face</i>Editar perfil</a></li>
                                                <li><a href="#" onclick="openPasswordDialog()"class = "black-text"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                                <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                                                <li><a href="#" onclick="logOut()"class = "black-text"><i class="material-icons">clear</i>Salir</a></li>
                                            </ul>
                                    </div>
                                </nav>
                            </div>
                            <!--Navegación lateral para dispositivos móviles-->
                            <ul class="sidenav" id="mobile-sidenav">
                                <li><a href="products.php"><i class="material-icons right">shopping_cart</i>Productos</a></li>
                                <li><a href="categorias.php"><i class="material-icons right">category</i>Categorías</a></li>
                                <li><a href="clients.php"><i class="material-icons right">supervisor_account</i>Clientes</a></li>
                                <li><a href="#" class="dropdown-trigger" data-target="dropdown-mobile"><i class="material-icons left">account_circle</i>Cuenta: <b>' . $_SESSION['usuario'] . '</b></a></li>
                                </ul>
                                <ul id="dropdown-mobile" class="dropdown-content dropdown">
                                <li><a href="#" onclick="openProfileDialog()"><i class="material-icons">face</i>Editar perfil</a></li>
                                <li><a href="#" onclick="openPasswordDialog()"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                                <li><a href="#" onclick="logOut()"><i class="material-icons">clear</i>Salir</a></li>
                            </ul>
                        </header>
                        <!--Contenido principal del documento-->
                        <main>
                        ');
                    } 
                }
            } else {
                header('location: main.php');
            }
        } else {
            // Se verifica si la página web actual es diferente a index.php (Iniciar sesión) y a register.php (Crear primer usuario) para direccionar a index.php, de lo contrario se muestra un menú vacío.
            if ($filename != 'index.php' && $filename != 'register.php' && $filename != 'password.php' && $filename != 'codigo.php' && $filename != 'clave.php') {
                header('location: index.php');
            } else {
                // Se imprime el código HTML para el encabezado del documento con un menú vacío cuando sea iniciar sesión o registrar el primer usuario.
                print('
                <header>
                
            </header>
                ');
            }
        }
    }

    /*
    *   Método para imprimir la plantilla del pie.
    *
    *   Parámetros: $controller (nombre del archivo que sirve como controlador de la página web).
    *
    *   Retorno: ninguno.
    */
    public static function footerTemplate($controller)
    {
        // Se comprueba si existe una sesión de administrador para imprimir el pie respectivo del documento.
        if (isset($_SESSION['idusuario'])) {
            $scripts = '
                <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/initialization.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/account.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/logout.js"></script>

                <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '"></script>
            ';
        } else {
            $scripts = '
                <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
                <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
                <script type="text/javascript" src="../../app/helpers/components.js"></script>
                <script type="text/javascript" src="../../app/controllers/dashboard/' . $controller . '"></script>
            ';
        }
        print('
        </main>
        <footer class="" >

                    ' . $scripts . '
                    </footer>
                </body>
            </html>
        ');
    }

    /*
    *   Método para imprimir las cajas de dialogo (modals) de editar pefil y cambiar contraseña.
    */
    private static function modals()
    {
        // Se imprime el código HTML de las cajas de dialogo (modals).
        print('
            <!-- Componente Modal para mostrar el formulario de editar perfil -->
            <div id="profile-modal" class="modal">
                <div class="modal-content">
                    <h4 class="center-align">Editar perfil</h4>
                    <form method="post" id="profile-form">
                        <div class="row">
                            <div class="input-field col l12 s12 m6">
                                <i class="material-icons prefix">email</i>
                                <input id="correo_electronico" type="email" name="correo_electronico" autocomplete="off" class="validate" required/>
                                <label for="correo_electronico">Correo</label>
                            </div>
                            <div class="input-field col l12 s12 m6">
                                <i class="material-icons prefix">person_pin</i>
                                <input id="usuario" type="text" name="usuario" class="validate" autocomplete="off" required/>
                                <label for="usuario">Alias</label>
                            </div>
                        </div>
                        <div class="row center-align">
                            <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Cancelar"><i class="material-icons">cancel</i></a>
                            <button type="submit" class="btn waves-effect blue tooltipped" data-tooltip="Guardar"><i class="material-icons">save</i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Componente Modal para mostrar el formulario de cambiar contraseña -->
            <div id="password-modal" class="modal">
                <div class="modal-content">
                    <h4 class="center-align">Cambiar contraseña</h4>
                    <form method="post" id="password-form">
                        <div class="row">
                            <div class="input-field col s12 m6 offset-m3">
                                <i class="material-icons prefix">security</i>
                                <input id="clave_actual" type="password" name="clave_actual" class="validate" autocomplete="off" required/>
                                <label for="clave_actual">Clave actual</label>
                            </div>
                        </div>
                        <div class="row center-align">
                            <label>CLAVE NUEVA</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">security</i>
                                <input id="clave_nueva_1" type="password" name="clave_nueva_1" autocomplete="off" class="validate" required/>
                                <label for="clave_nueva_1">Clave</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">security</i>
                                <input id="clave_nueva_2" type="password" name="clave_nueva_2" autocomplete="off" class="validate" required/>
                                <label for="clave_nueva_2">Confirmar clave</label>
                            </div>
                        </div>
                        <div class="row center-align">
                            <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Cancelar"><i class="material-icons">cancel</i></a>
                            <button type="submit" class="btn waves-effect blue tooltipped" data-tooltip="Guardar"><i class="material-icons">save</i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="device-modal" class="modal ">

            <form method="post" id="device-form" enctype="multipart/form-data">
            <input class="hide" type="text" id="txtIdX" name="txtIdX" />
            </form>
                <div class="modal-content">
                    <h4 class="center-align">Mis dispositivos</h4>

                    <div class="row" id="devices">


                    
                    </div>

                    <div class="row center-align">
                            <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Salir"><i class="material-icons">cancel</i></a>
                        </div>
                    
                </div>
            </div>



            <div id="history-modal" class="modal ">
                <div class="modal-content">


                    <h4 id="modal-title" class="center-align">Historial de sesiones </h4><br>


                        <table class=" center-align striped centered responsive-table">
                            <thead>
                                <tr id="tableHeader">
                                <th>Fecha</th>
                                <th>Hora</th>
                                
                                </tr>
                            </thead>
                            <tbody id="historial">
                            </tbody>
                            </table>


                    
                    <br>
                    <div class="row center-align">
                            <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Salir"><i class="material-icons">cancel</i></a>
                        </div>
                    </div>
                    </form>
                </div>
        </div>
        ');
    }
}