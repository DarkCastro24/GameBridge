<?php
/*
*   Clase para definir las plantillas de las páginas web del sitio público.
*/

// Se incluye la configuración de Google OAuth
require_once(__DIR__ . '/google_config.php');

class Public_Page
{
    /*
    *   Método para imprimir la plantilla del encabezado.
    *
    *   Parámetros: $title (título de la página web).
    *
    *   Retorno: ninguno.
    */
    public static function headerTemplate($title, $css)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
        session_start();
        $filename = basename($_SERVER['PHP_SELF']);

        // Se imprime el código HTML para el encabezado del documento.
        print('
            <!DOCTYPE html>
            <html lang="es">
                <head>
                    <meta charset="utf-8">
                    <title>GameBridge | ' . $title . '</title>
                    <link type="text/css" rel="stylesheet" href="../../resources/css/materialize.min.css"/>
                    <link type="text/css" rel="stylesheet" href="../../resources/css/material_icons.css"/>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                    <link type="text/css" rel="stylesheet" href="../../resources/css/' . $css . '.css"/>
                    <link rel="icon" type="image/png" href="../../resources/img/brand/Logo.png" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        ');

        // Solo cargar el SDK de Google en la página de login
        if ($filename === 'login.php') {
            print('
                    <!-- Google Sign-In SDK (solo en login) -->
                    <script src="https://accounts.google.com/gsi/client" async defer></script>
            ');
        }

        print('
                </head>
                <body>
        ');

        // Se obtiene el nombre del archivo de la página web actual.
        // Se comprueba si existe una sesión de cliente para mostrar el menú de opciones, de lo contrario se muestra otro menú.
        if (isset($_SESSION['idcliente'])) {
            // Se verifica si la página web actual es diferente a login.php y register.php, de lo contrario se direcciona a index.php
            if ($filename != 'login.php' && $filename != 'singin.php') {
                // Se llama al método que contiene el código de las cajas de dialogo (modals).
                self::modals();
                if ($filename != 'password.php') {
                    print('        
                    <header>
                          <div class="navbar-fixed" id="navbar">
                              <nav class="navbarColor">
                                  <div class="nav-wrapper">                  
                                  <a title="Logo" href="index.php"><img src="../../resources/img/brand/Navbar.png" class="hide-on-med-and-down" alt="Logo" /></a>
                                      <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                      <ul class="right hide-on-med-and-down">
                                          <li><a href="#" class="dropdown-trigger" data-target="dropdown"><i class="material-icons left">verified_user</i><b>' . (isset($_SESSION['correo_electronico']) ? $_SESSION['correo_electronico'] : 'Usuario') . '</b></a></li>
                                          <li><a href="hardware.php">Hardware</a></li>
                                          <li><a href="perifericos.php">Periféricos</a></li>
                                          <li><a href="accesorios.php">Accesorios</a></li>
                                          <li><a class="tooltipped" data-tooltip="Carrito de compras" href="carrito.php"><i class="material-icons">local_grocery_store</i></a></li>
                                          <li><a class="tooltipped" data-tooltip="Historial productos" href="historial.php"><i class="material-icons">book</i></a></li>
                                          <li><a class="tooltipped" data-tooltip="Pedidos" href="pedidos.php"><i class="material-icons">local_shipping</i></a></li>    
                                      </ul>
                                    <ul id="dropdown" class="dropdown-content">
                                    <li><a href="#" onclick="openProfileDialog()" class = "black-text"><i class="material-icons">face</i>Editar perfil</a></li>
                                    <li><a href="#" onclick="openPasswordDialog()"class = "black-text"><i class="material-icons">lock</i>Cambiar clave</a></li>
                                    <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                                    <li><a href="#" onclick="logOut()"class = "black-text"><i class="material-icons">clear</i>Salir</a></li>
                                     </ul>
                                  </div>
                              </nav>
                          </div>
                          <!--Navegación lateral para dispositivos móviles-->
                          <ul class="sidenav centrar" id="mobile-sidenav">
                              <a title="Logo" href="index.php"><img src="../../resources/img/brand/logo_submenu.png" class="logo-submenu" alt="Logo-Submenu" /></a><hr>
                              <li><a href="#" class="dropdown-trigger" data-target="dropdown-mobile"><i class="material-icons left">verified_user</i> <b>' . (isset($_SESSION['correo_electronico']) ? $_SESSION['correo_electronico'] : 'Usuario') . '</b></a></li>
                              <li><a href="hardware.php"><i class="material-icons">desktop_windows</i><p>Hardware</p></a></li>
                              <li><a href="perifericos.php"><i class="material-icons">headset_mic</i><p>Perifericos</p></a></li>
                              <li><a href="accesorios.php"><i class="material-icons">mic</i><p>Accesorios</p></a></li>
                              <hr>
                              <li><a href="carrito.php"><i class="material-icons">local_grocery_store</i><p>Carrito</p></a></li>
                              <li><a href="historial.php"><i class="material-icons">book</i><p>Historial productos</p></a></li>
                              <li><a href="pedidos.php"><i class="material-icons">local_shipping</i><p>Pedidos</p></a></li>
                          </ul>
                          <ul id="dropdown-mobile" class="dropdown-content dropdown">
                    <li><a href="#" onclick="openProfileDialog()"><i class="material-icons">face</i>Editar perfil</a></li>
                    <li><a href="#" onclick="openPasswordDialog()"><i class="material-icons">lock</i>Cambiar clave</a></li>
                    <li><a href="#" onclick="viewDevices()"class = "black-text"><i class="material-icons">devices</i>Mis dispositivos</a></li>
                    <li><a href="#" onclick="logOut()"><i class="material-icons">clear</i>Salir</a></li>
                    </ul>
                      </header>
                      <main> 
                  ');
                }
            } else {
                header('location: index.php');
            }
        } else {
            if ($filename != 'pedidos.php' && $filename != 'historial.php' && $filename != 'carrito.php') {
                if ($filename != 'login.php' && $filename != 'codigo.php' && $filename != 'clave.php' && $filename != 'password.php') {
                    print('
                    <header>
                        <div class="navbar-fixed" id="navbar">
                            <nav class="navbarColor">
                                <div class="nav-wrapper">                  
                                <a title="Logo" href="index.php"><img src="../../resources/img/brand/Navbar.png" class="hide-on-med-and-down" alt="Logo" /></a>
                                    <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                    <ul class="right hide-on-med-and-down">
                                        <li><a href="hardware.php">Hardware</a></li>
                                        <li><a href="perifericos.php">Periféricos</a></li>
                                        <li><a href="accesorios.php">Accesorios</a></li>
                                        <li><a href="singin.php">Registrate</a></li>
                                        <li><a class="tooltipped" data-tooltip="Iniciar sesión" href="login.php"><i class="material-icons">person</i></a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <!--Navegación lateral para dispositivos móviles-->
                        <ul class="sidenav centrar" id="mobile-sidenav">
                            <a title="Logo" href="index.php"><img src="../../resources/img/brand/logo_submenu.png" class="logo-submenu" alt="Logo-Submenu" /></a><hr>
                            <li><a href="hardware.php"><i class="material-icons">desktop_windows</i><p>Hardware</p></a></li>
                            <li><a href="perifericos.php"><i class="material-icons">headset_mic</i><p>Perifericos</p></a></li>
                            <li><a href="accesorios.php"><i class="material-icons">mic</i><p>Accesorios</p></a></li>
                            <hr>
                            <li><a href="singin.php"><i class="material-icons">add</i><p>Registrarse</p></a></li>
                            <li><a href="LogIn.php"><i class="material-icons">person</i><p>Iniciar Sesion</p></a></li>
                        </ul>
                    </header>
                    <main> 
                ');
                }
            } else {
                header('location: login.php');
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
        $filename = basename($_SERVER['PHP_SELF']);

        // googleScript eliminado: la inicialización del SDK de Google
        // se maneja completamente dentro de login.js para evitar
        // problemas de orden de carga con async defer.
        $googleScript = '';

        if (isset($_SESSION['idcliente'])) {
            print('
    </main> 
    <footer class="page-footer footer-modern" id="footer">
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">call</i> Contáctanos</h6>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/telefono.png" alt="telefono">
                                <p><strong>Teléfono:</strong><br>7988-5288</p>
                            </div>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/whatsapp.png" alt="whatsapp">
                                <p><strong>WhatsApp:</strong><br>2593-1265</p>
                            </div>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/correo.png" alt="correo">
                                <p><strong>Email:</strong><br>GbStore@gmail.com</p>
                            </div>
                        </div>
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">share</i> Síguenos</h6>
                            <p style="font-size: 13px; color: rgba(255,255,255,0.8);">Mantente conectado con nosotros</p>
                            <div class="social-icons">
                                <a href="https://facebook.com/gamebridgestore" class="social-btn" target="_blank" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://instagram.com/gamebridgesv" class="social-btn" target="_blank" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://twitter.com/gamebridgesv" class="social-btn" target="_blank" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://linkedin.com/company/gamebridge" class="social-btn" target="_blank" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col s12 m6 l3 footer-section hide-on-small-and-down">
                            <h6><i class="material-icons tiny">location_on</i> Ubicación</h6>
                            <p style="font-size: 13px; line-height: 1.8;">
                                <strong>GameBridge Store</strong><br>
                                Avenida Aguilares 218<br>
                                San Salvador, CP 1101<br>
                                El Salvador
                            </p>
                        </div>
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">schedule</i> Horarios</h6>
                            <p style="font-size: 13px; line-height: 1.8;">
                                <strong>Lunes - Viernes:</strong><br>09:00 AM - 6:00 PM<br><br>
                                <strong>Sábado:</strong><br>10:00 AM - 4:00 PM<br><br>
                                <strong>Domingo:</strong><br>Cerrado
                            </p>
                        </div>
                    </div>
                    <div class="footer-divider">
                        <p>Partner Oficial</p>
                    </div>
                </div>
                <div class="footer-copyright-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col s12 m6 left-align">
                                <p>&copy; 2026 <strong>GameBridge Store</strong>. Todos los derechos reservados.</p>
                            </div>
                            <div class="col s12 m6 right-align hide-on-small-and-down">
                                <p>Desarrollado con <i class="tiny material-icons" style="vertical-align: middle;">favorite</i> en El Salvador</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../app/helpers/components.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/account.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/logout.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/' . $controller . '"></script>
    ' . $googleScript . '
    </body>
    </html>
                ');
            } else {
                print('
    </main> 
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../app/helpers/components.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/account.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/logout.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/' . $controller . '"></script>
    </body>
    </html> 
                ');
        } else {
            if ($filename != 'login.php' && $filename != 'codigo.php' && $filename != 'clave.php') {
                print('<footer class="page-footer footer-modern" id="footer">
                <div class="container">
                    <div class="row">
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">call</i> Contáctanos</h6>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/telefono.png" alt="telefono">
                                <p><strong>Teléfono:</strong><br>7988-5288</p>
                            </div>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/whatsapp.png" alt="whatsapp">
                                <p><strong>WhatsApp:</strong><br>2593-1265</p>
                            </div>
                            <div class="footer-contact">
                                <img src="../../resources/img/iconos/correo.png" alt="correo">
                                <p><strong>Email:</strong><br>GbStore@gmail.com</p>
                            </div>
                        </div>
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">share</i> Síguenos</h6>
                            <p style="font-size: 13px; color: rgba(255,255,255,0.8);">Mantente conectado con nosotros</p>
                            <div class="social-icons">
                                <a href="https://facebook.com/gamebridgestore" class="social-btn" target="_blank" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://instagram.com/gamebridgesv" class="social-btn" target="_blank" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://twitter.com/gamebridgesv" class="social-btn" target="_blank" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://linkedin.com/company/gamebridge" class="social-btn" target="_blank" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col s12 m6 l3 footer-section hide-on-small-and-down">
                            <h6><i class="material-icons tiny">location_on</i> Ubicación</h6>
                            <p style="font-size: 13px; line-height: 1.8;">
                                <strong>GameBridge Store</strong><br>
                                Avenida Aguilares 218<br>
                                San Salvador, CP 1101<br>
                                El Salvador
                            </p>
                        </div>
                        <div class="col s12 m6 l3 footer-section">
                            <h6><i class="material-icons tiny">schedule</i> Horarios</h6>
                            <p style="font-size: 13px; line-height: 1.8;">
                                <strong>Lunes - Viernes:</strong><br>09:00 AM - 6:00 PM<br><br>
                                <strong>Sábado:</strong><br>10:00 AM - 4:00 PM<br><br>
                                <strong>Domingo:</strong><br>Cerrado
                            </p>
                        </div>
                    </div>
                    <div class="footer-divider">
                        <p>Partner Oficial</p>
                    </div>
                </div>
                <div class="footer-copyright-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col s12 m6 left-align">
                                <p>&copy; 2026 <strong>GameBridge Store</strong>. Todos los derechos reservados.</p>
                            </div>
                            <div class="col s12 m6 right-align hide-on-small-and-down">
                                <p>Desarrollado con <i class="tiny material-icons" style="vertical-align: middle;">favorite</i> en El Salvador</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>');
            }
            print('
    </main> 
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../app/helpers/components.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/' . $controller . '"></script>
    ' . $googleScript . '
    </body>
    </html>
            ');
        // Nota: $controller ya se carga antes de $googleScript,
        // así handleGoogleLogin está disponible cuando Google SDK la busca.
        }
    }

    /*
    *   Método para imprimir las cajas de dialogo (modals).
    */
    private static function modals()
    {
        print('
            <!-- Componente Modal para mostrar el formulario de editar perfil -->
            <div id="profile-modal" class="modal">
                <div class="modal-content">
                    <h4 class="center-align">Editar perfil</h4>
                    <form method="post" id="profile-form">
                        <div class="row">
                            <div class="input-field col l6 s12 m6">
                                <i class="material-icons prefix">mail</i>
                                <input id="correo_electronico" type="email" name="correo_electronico" class="validate" required/>
                                <label for="correo_electronico">Correo</label>
                            </div>
                            <div class="input-field col l6 s12 m6">
                                 <i class="material-icons prefix">person</i>
                                <input id="nombres" type="text" name="nombres" class="validate" required/>
                                <label for="nombres">Nombres</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col l6 s12 m6">
                                <i class="material-icons prefix">person</i>
                                <input id="apellidos" type="text" name="apellidos" class="validate" required/>
                                <label for="apellidos">Apellidos</label>
                            </div>
                            <div class="input-field col l6 s12 m6">
                                <i class="material-icons prefix">fingerprint</i>
                                <input id="dui" type="text" name="dui" class="validate" required/>
                                <label for="dui">DUI</label>
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
                                <input id="clave_actual" type="password" name="clave_actual" class="validate" required/>
                                <label for="clave_actual">Clave actual</label>
                            </div>
                        </div>
                        <div class="row center-align">
                            <label>CLAVE NUEVA</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">security</i>
                                <input id="clave_nueva_1" type="password" name="clave_nueva_1" class="validate" required/>
                                <label for="clave_nueva_1">Clave</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">security</i>
                                <input id="clave_nueva_2" type="password" name="clave_nueva_2" class="validate" required/>
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

            <div id="device-modal" class="modal">
                <form method="post" id="history-form" enctype="multipart/form-data">
                    <input class="hide" type="text" id="txtIdX" name="txtIdX" />
                </form>
                <div class="modal-content">
                    <h4 class="center-align">Mis dispositivos</h4>
                    <div class="row" id="devices"></div>
                    <div class="row center-align">
                        <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Salir"><i class="material-icons">cancel</i></a>
                    </div>
                </div>
            </div>

            <div id="history-modal" class="modal">
                <div class="modal-content">
                    <h4 id="modal-title" class="center-align">Historial de sesiones</h4><br>
                    <table class="center-align striped centered responsive-table">
                        <thead>
                            <tr id="tableHeader">
                                <th>Fecha</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody id="historial"></tbody>
                    </table>
                    <br>
                    <div class="row center-align">
                        <a href="#" class="btn waves-effect red tooltipped modal-close" data-tooltip="Salir"><i class="material-icons">cancel</i></a>
                    </div>
                </div>
            </div>
        ');
    }
}
?>