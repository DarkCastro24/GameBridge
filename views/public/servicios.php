<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Servicios','public');
?>
<div id="Servicios" class="container">
    <div class="row">
        <div class="col s12 m12 l7 xl6 ">
            <img src="../../resources/img/servicios/Envio.png"  alt="Servicio1">
        </div>
        <div id="descripcion" class="col s12 m12 l5 xl6">
           <div class="row">
                <h5>Servicio de entrega a domicilio</h5>
           </div>
           <div id="DescripcionServicio" class="row">           
                <p>Tu comodidad es una de nuestras mayores prioridades por eso contamos con el mejor servicio de entrega a domicilio del pais seguro y eficiente gratuito con en un plazo de 3 a 5 dias habiles enviamos tus productos a tu residencia u oficina en toda el area metropolitana de San Salvador en compras que superen los 50$.</p>
           </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m12 l7 xl6 ">
            <img src="../../resources/img/servicios/Garantia.png"  alt="Servicio1">
        </div>
        <div id="descripcion" class="col s12 m12 l5 xl6">
           <div class="row">
                <h5>Garantia en todos tus productos</h5>
           </div>
           <div id="DescripcionServicio" class="row">           
                <p>Que ahorres y te sientas seguro de realizar una inversion es nuestra prioridad por esa razon todos nuestros productos poseen garantia local en caso de algun desperfecto puedes llevarlo a nuestro local y nosotros te ayudaremos a solucionarlo sin ningun coste adicional siempre y cuando el periodo de garatia siga vigente contamos con el mejor equipo tecnico para la reparacion de componentes electronicos.</p>
           </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12 l7 xl6 ">
            <img src="../../resources/img/servicios/Tecnologia.png"  alt="Servicio1">
        </div>
        <div id="descripcion" class="col s12 m12 l5 xl6">
           <div class="row">
                <h5>Ensamblaje de ordenadores de escritorio</h5>
           </div>
           <div id="DescripcionServicio" class="row">           
                <p>Nuestros productos se encuentran actualizados en relacion a los lanzamientos mas recientes realizados a nivel internacional en todas las categorias de hardware con las que contamos ademas tienes la opcion de solicitar un ensamblaje en caso que adquieras tus componentes nostros los instalamos en tu gabinete por un costo de 5$ manejo de cables y limpieza de componentes viene incluido</p>
           </div>
        </div>
    </div>
</div>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('index.js');
?>