<?php
/*
*   Clase para definir las plantillas de las páginas web del sitio público.
*/
class Public_Page
{
    /*
    *   Método para imprimir la plantilla del encabezado.
    *
    *   Parámetros: $title (título de la página web).
    *
    *   Retorno: ninguno.
    */
    public static function headerTemplate($title,$css)
    {
              // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en las páginas web.
              session_start();
              // Se imprime el código HTML para el encabezado del documento.
              print('
                  <!DOCTYPE html>
                  <html lang="es">
                      <head>
                          <meta charset="utf-8">
                          <title>GameBridge | '.$title.'</title>
                          <link type="text/css" rel="stylesheet" href="../../resources/css/materialize.min.css"/>
                          <link type="text/css" rel="stylesheet" href="../../resources/css/material_icons.css"/>
                          <link type="text/css" rel="stylesheet" href="../../resources/css/'.$css.'.css"/>
                          <link rel="icon" type="image/png" href="../../resources/img/brand/Logo.png" />
                          <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                      </head>
                      <body>
              ');
              // Se obtiene el nombre del archivo de la página web actual.
              $filename = basename($_SERVER['PHP_SELF']);
              // Se comprueba si existe una sesión de cliente para mostrar el menú de opciones, de lo contrario se muestra otro menú.
              if (isset($_SESSION['idcliente'])) {
                  // Se verifica si la página web actual es diferente a login.php y register.php, de lo contrario se direcciona a index.php
                  if ($filename != 'login.php'&& $filename != 'singin.php') {
                       // Se llama al método que contiene el código de las cajas de dialogo (modals).
                    self::modals();
                    if ($filename != 'autentication.php' && $filename != 'password.php') {
                        print('        
                        <header>
                              <div class="navbar-fixed" id="navbar">
                                  <nav class="navbarColor">
                                      <div class="nav-wrapper">                  
                                      <a title="Logo" href="index.php"><img src="../../resources/img/brand/Navbar.png" class="hide-on-med-and-down" alt="Logo" /></a>
                                          <a href="#" data-target="mobile-sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                                          <ul class="right hide-on-med-and-down">
                                              <li><a href="#" class="dropdown-trigger" data-target="dropdown"><i class="material-icons left">verified_user</i><b>' . $_SESSION['correo_electronico'] . '</b></a></li>
                                              <li><a href="hardware.php">Hardware</a></li>
                                              <li><a href="perifericos.php">Periféricos</a></li>
                                              <li><a href="accesorios.php">Accesorios</a></li>
                                              <li><a href="Servicios.php">Servicios</a></li>
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
                                  <li><a href="#" class="dropdown-trigger" data-target="dropdown-mobile"><i class="material-icons left">verified_user</i> <b>' . $_SESSION['correo_electronico'] . '</b></a></li>
                                  <li><a href="hardware.php"><i class="material-icons">desktop_windows</i><p>Hardware</p></a></li>
                                  <li><a href="perifericos.php"><i class="material-icons">headset_mic</i><p>Perifericos</p></a></li>
                                  <li><a href="accesorios.php"><i class="material-icons">mic</i><p>Accesorios</p></a></li>
                                  <li><a href="Servicios.php"><i class="material-icons">local_shipping</i><p>Servicios</p></a></li>
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
                  // Se verifica si la página web actual es diferente a index.php (Iniciar sesión) y a register.php (Crear primer usuario) para direccionar a index.php, de lo contrario se muestra un menú vacío.
                  if ($filename != 'pedidos.php' && $filename != 'historial.php' && $filename != 'carrito.php') {
                      if ($filename != 'login.php' && $filename != 'codigo.php' && $filename != 'clave.php' && $filename != 'autentication.php' && $filename != 'password.php') {
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
                                            <li><a href="servicios.php">Servicios</a></li>
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
                                <li><a href="Servicios.php"><i class="material-icons">local_shipping</i><p>Servicios</p></a></li>
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
        if (isset($_SESSION['idcliente'])) {
            if ($filename != 'autentication.php') {
                print('
    </main> 

    <footer class="page-footer" id="footer">
                <div class="container">
                    <div class="row">
                        
                        <div id="contacto" class="col s12 m6 l6 xl3">
                            
                            <h6>Contactanos</h6>
                            <div class="row espacioIconos">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/telefono.png" alt="telefono">
                                </div>
                                <div class="col s9">
                                    <p>7988-5288</p>
                                </div>
                            </div>
                            <div class="row espacioIconos">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/whatsapp.png" alt="whatsapp">
                                </div>
                                <div class="col s9">
                                    <p>2593-1265</p>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/correo.png" alt="correo">
                                </div>
                                <div class="col s9">
                                    <p>GbStore@gmail.com</p>
                                </div>
                            </div>        
                        </div>
        
                        <div id="redes" class="col s12 m6 l6 xl3">
                            <h6>Redes sociales</h6>
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/facebook.png" alt="facebook">
                                </div>
                                <div class="col s9">
                                    <p>Gamebridge Store</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/instagram.png" alt="instagram">
                                </div>
                                <div class="col s9">
                                    <p>Gamebridge Store SV</p>
                                </div>
                            </div>
                                
                        </div>
                        <div class="col s12 m6 l6 xl3 textoDireccion hide-on-med-and-down ">
                   
                            <h6>Ubicación</h6>
                            <div class="row">
                                <div class="col s9">
                                    <p>Avenida Aguilares 218 San Salvador CP, 1101</p>
                                </div>
                            </div>
                                
                        </div>
                        <div id="ubicacion" class="col s12 m6 l6 xl3">
                            <h6>Mapa</h6>
                            <img src="../../resources/img/servicios/Ubicacion.jpg" alt="Ubicacion">
                        </div>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container centrar">
                        © 2021 Gamebridge Derechos Reservados
                    </div>
                </div>
            </footer>
    <!--Importación de archivos JavaScript al final del cuerpo para una carga optimizada-->
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../app/helpers/components.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/account.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/logout.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/'.$controller.'"></script>
    </body>
    </html>
    ');
    } else {
        print('
        </main> 
            <!--Importación de archivos JavaScript al final del cuerpo para una carga optimizada-->
            <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
            <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
            <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
            <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
            <script type="text/javascript" src="../../app/helpers/components.js"></script>
            <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
            <script type="text/javascript" src="../../app/controllers/public/account.js"></script>
            <script type="text/javascript" src="../../app/controllers/public/logout.js"></script>
        <script type="text/javascript" src="../../app/controllers/public/'.$controller.'"></script>
        </body>
        </html> 
        ');
            }
            
            

        }else{

            if ($filename != 'login.php' && $filename != 'autentication.php' && $filename != 'codigo.php' && $filename != 'clave.php') {
                print('<footer class="page-footer" id="footer">
                <div class="container">
                    <div class="row">
                        
                        <div id="contacto" class="col s12 m6 l6 xl3">
                            
                            <h6>Contactanos</h6>
                            <div class="row espacioIconos">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/telefono.png" alt="telefono">
                                </div>
                                <div class="col s9">
                                    <p>7988-5288</p>
                                </div>
                            </div>
                            <div class="row espacioIconos">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/whatsapp.png" alt="whatsapp">
                                </div>
                                <div class="col s9">
                                    <p>2593-1265</p>
                                </div>
                            </div>
        
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/correo.png" alt="correo">
                                </div>
                                <div class="col s9">
                                    <p>GbStore@gmail.com</p>
                                </div>
                            </div>        
                        </div>
        
                        <div id="redes" class="col s12 m6 l6 xl3">
                            <h6>Redes sociales</h6>
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/facebook.png" alt="facebook">
                                </div>
                                <div class="col s9">
                                    <p>Gamebridge Store</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s3">
                                    <img src="../../resources/img/iconos/instagram.png" alt="instagram">
                                </div>
                                <div class="col s9">
                                    <p>Gamebridge Store SV</p>
                                </div>
                            </div>
                                
                        </div>
                        <div class="col s12 m6 l6 xl3 textoDireccion hide-on-med-and-down ">
                   
                            <h6>Ubicación</h6>
                            <div class="row">
                                <div class="col s9">
                                    <p>Avenida Aguilares 218 San Salvador CP, 1101</p>
                                </div>
                            </div>
                                
                        </div>
                        <div id="ubicacion" class="col s12 m6 l6 xl3">
                            <h6>Mapa</h6>
                            <img src="../../resources/img/servicios/Ubicacion.jpg" alt="Ubicacion">
                        </div>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container centrar">
                        © 2021 Gamebridge Derechos Reservados
                    </div>
                </div>
            </footer>');
            }
            print('
        
    </main> 
    <!--Importación de archivos JavaScript al final del cuerpo para una carga optimizada-->
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../app/controllers/initialization.js"></script>
    <script type="text/javascript" src="../../resources/js/materialize.min.js"></script>
    <script type="text/javascript" src="../../resources/js/sweetalert.min.js"></script>
    <script type="text/javascript" src="../../app/helpers/components.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/initialization.js"></script>
    <script type="text/javascript" src="../../app/controllers/public/'.$controller.'"></script>
</body>
</html>

                   
        ');
        }

        // Se imprime el código HTML para el pie del documento.
        
    }

    /*
    *   Método para imprimir las cajas de dialogo (modals).
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


            <div id="device-modal" class="modal ">

            <form method="post" id="history-form" enctype="multipart/form-data">
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
?>