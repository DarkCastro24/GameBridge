<?php
// Se incluye la clase con las plantillas del documento.
require_once('../../app/helpers/dashboard_page.php');
// Se imprime la plantilla del encabezado enviando el título de la página web.
Dashboard_Page::headerTemplate('Menu principal');
?>
    <!-- Se muestra un saludo de acuerdo con la hora del cliente -->
    <div class="row">
        <h4 class="center-align blue-text" id="greeting"></h4>
        <input class="hide" type="text" id="user" name="user" value="<?php echo $_SESSION['usuario'] ?>"/>
    </div>                                                                                                                                       
    <!-- Se muestran las gráfica de acuerdo con algunos datos disponibles en la base de datos -->
    <div class="row">
        <h5 class="centrar">Gráfica de los cinco productos más vendidos</h5>
        <div class="col s12 m8 offset-m2">
            <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
            <canvas id="chart2"></canvas>                                                                                                       <br><br><br>
        </div>
    </div>   
    <!-- Se muestran las gráfica de acuerdo con algunos datos disponibles en la base de datos -->
    <div class="row">
        <h5 class="centrar">Gráfica de las cinco categorías más vendidas</h5>
        <div class="col s12 m8 offset-m2">
            <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
            <canvas id="chart1"></canvas>                                                                                                       <br><br><br>
        </div>
    </div>                                                                                                                                          
    <!-- Se muestran las gráfica de acuerdo con algunos datos disponibles en la base de datos -->
    <div class="row">
        <h5 class="centrar">Gráfica de las cinco marcas con más productos vendidos</h5>
        <div class="col s12 m8 offset-m2">
            <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
            <canvas id="chart3"></canvas>                                                                                                       <br><br><br>
        </div>
    </div>                                                                                                                                          
    <!-- Se muestran las gráfica de acuerdo con algunos datos disponibles en la base de datos -->
    <div class="row">
        <h5 class="centrar">Gráfica de cantidad de productos vendidos en la última semana</h5>
        <div class="col s12 m8 offset-m2">
            <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
            <canvas id="chart4"></canvas>                                                                                                       <br><br><br>
        </div>
    </div>                                                                                                                                         
    <!-- Se muestran las gráfica de acuerdo con algunos datos disponibles en la base de datos -->
    <div class="row">
        <h5 class="centrar">Gráfica de los cinco clientes con más pedidos realizados</h5>
        <div class="col s12 m8 offset-m2">
            <!-- Se muestra una gráfica de barra con la cantidad de productos por categoría -->
            <canvas id="chart5"></canvas>                                                                                                       <br><br><br>
        </div>
    </div>                                                                                                                                          

<!-- Importación del archivo para generar gráficas en tiempo real. Para más información https://www.chartjs.org/ -->
<script type="text/javascript" src="../../resources/js/chart.js"></script>
<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Dashboard_Page::footerTemplate('main.js');
?>
</body>
</html>