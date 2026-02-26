<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Menu principal','public');
?>
<div id="contenedor" class="container-fluid">
<div class="container-fluid" id="carrusel">
    <div class="slider" >
        <ul class="slides z-depth-3">
            <li>
                <img src="../../resources/img/carousel/Carusel1.jpg ">
                <div class="caption center-align">
                    <h3>Contamos con un extenso catálogo</h3>
                    <h5>"Para tus necesidades de hardware"</h5>
                </div>
            </li>
            <li>
                <img src="../../resources/img/carousel/Carusel2.jpg">
                <div class="caption left-align">
                    <h3>Contamos con accesorios para tu ordenador</h3>
                    <h5>"Contamos un gran catalogo de accesorios"</h5>
                </div>
            </li>
            <li>
                <img src="../../resources/img/carousel/Carusel3.jpg">
                <div class="caption right-align">
                    <h3>Contamos con instalaciones modernas</h3>
                    <h5>"Adecuadas para tu seguridad y comodidad"</h5>
                </div>
            </li>
            <li>
                <img src="../../resources/img/carousel/Carusel4.jpg">
                <div class="caption center-align">
                    <h3>Contamos con productos de calidad al mejor precio</h3>
                    <h5>"Calidad al mejor precio es nuestro objetivo"</h5>
                </div>
            </li>
        </ul>
    </div>
</div>
<div class="container" id= "Ofertas">
    <div class="row">
        <center><a class="centrar tamañoTitulos letranegra" href="hardware.php"> Conoce nuestras <b>ofertas especiales</b></a></center><br><br>
        <div id="cartas" class="container-fluid">
            <div class="col s12 m12 lg6 xl4">
                <div class="card z-depth-3">
                    <div class="card-image">
                        <img src="../../resources/img/ofertas/oferta2.jpg">
                    </div><hr>
                    <div class="card-content tamañoCarta justificar">
                        <p class="tituloCarta">Procesador Ryzen 7 3700X</p>
                        <p>Ryzen 7 3700X ofrece ventajas como su arquitectura a 7nm ZEN 2 que ofrece una increible eficiencia energetica 
                        de 65 W, soporte PCIe 4.0, y tiene 8 núcleos y 16 hilos</p><br>
                        <p><b>Precio: </b>320$</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 lg6 xl4">
                <div class="card z-depth-3">
                    <div class="card-image">
                        <img src="../../resources/img/ofertas/oferta1.jpg">
                    </div><hr>
                    <div class="card-content tamañoCarta justificar">
                        <p class="tituloCarta">Procesador Ryzen 7 3600x</p>
                        <p>Ryzen 7 3600x ofrece ventajas como su arquitectura a 7nm ZEN 2 que ofrece una increible eficiencia energetica 
                        de 65 W, soporte PCIe 4.0, y tiene 6 núcleos y 12 hilos</p><br>
                        <p><b>Precio: </b>250$</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 lg6 xl4">
                <div class="card z-depth-3">
                    <div class="card-image">
                        <img src="../../resources/img/ofertas/oferta3.jpg">
                    </div><hr>
                    <div class="card-content tamañoCarta justificar">
                        <p class="tituloCarta">Procesador Ryzen 9 3900X</p>
                        <p>Ryzen 9 3900X ofrece ventajas como su arquitectura a 7nm ZEN 2 que ofrece una increible eficiencia energetica 
                        de 65 W, soporte PCIe 4.0, y tiene 12 núcleos y 24 hilos</p><br>
                        <p><b>Precio: </b>500$</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" id="Ventajas">
    <div class="section">
        <h3 class="centrar tamañoTitulos">Ventajas de utilizar <b>nuestros servicios</b></h3>
        <div class="row">
            <div class="col s12 m12 lg3 xl3">
                <div class="container-fluid cartaVentaja">
                    <div class="icon-block">
                        <h2 class="center brown-text"><i class="material-icons">local_shipping</i></h2>
                        <h5 class="center">Entrega gratuita de tu compra</h5>
                        <p>Contamos con servicio de entrega gratuito con en un plazo de 3 a 5 dias hábiles en toda el área metropolitana de San Salvador.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 lg3 xl3">
                <div class="container-fluid cartaVentaja">
                    <div class="icon-block">
                        <h2 class="center brown-text"><i class="material-icons">support_agent</i></h2>
                        <h5 class="center">Excelente soporte técnico</h5>
                        <p>En caso de algún inconveniente contamos con soporte técnico en caso tengas algun problema con tus componentes adquiridos en gamebridge.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 lg3 xl3">
                <div class="container-fluid cartaVentaja">
                    <div class="icon-block">
                        <h2 class="center brown-text"><i class="material-icons">verified_user</i></h2>
                        <h5 class="center">Garantía en tus compras</h5>
                        <p >Todos nuestros productos poseen garantía local en caso de algun desperfecto puedes llevarlo a nuestro local y nosotros te ayudaremos a solucionarlo.</p>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 lg3 xl3">
                <div class="container-fluid cartaVentaja">
                    <div class="icon-block">
                        <h2 class="center brown-text"><i class="material-icons">system_security_update_good</i></h2>
                        <h5 class="center">Ultimas tecnologías del mercado</h5>
                        <p >Nuestros productos se encuentran actualizados en relacion a los lanzamientos mas recientes realizados a nivel internacional para brindarte las mejores tecnologías</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><br><br>
<!-- SECCIONES DE CATEGORÍAS Y PRODUCTOS -->
<div id="hardware-categories"></div><br><br>
<div id="perifericos-categories"></div><br><br>
<div id="accesorios-categories"></div><br><br>
<div id="top-sold-container"></div><br><br>

<center><A class="centrar tamañoTitulos letranegra" href="hardware.php"> Conoce parte de nuestra gran variedad <b>de productos</b></A></center><br><br>
<div href="hardware.php" class="slider" >
    <ul class="slides z-depth-3">
        <li>
            <img src="../../resources/img/carousel/procesadores.jpg ">
        </li>
        <li>
            <img src="../../resources/img/carousel/tarjetas.jpg">
        </li>
        <li>
            <img src="../../resources/img/carousel/motherboard.jpg">
        </li>
        <li>
            <img src="../../resources/img/carousel/fuentes.jpg">
        </li>
    </ul>
</div><br><br><br>


<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('index.js');
?>



        
   