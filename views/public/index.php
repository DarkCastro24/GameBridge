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
<!-- SECCIONES DE CATEGORÍAS Y PRODUCTOS -->
<div id="top-sold-container"></div><br><br>
<div id="hardware-categories"></div><br><br>
<div id="perifericos-categories"></div><br><br>
<div id="accesorios-categories"></div><br><br>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('index.js');
?>



        
   