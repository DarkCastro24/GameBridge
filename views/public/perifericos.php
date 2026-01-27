<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Perifericos','public');
?>

<div id="Seleccion-Catalogo" class="container-fluid margen">
    <div class="row margenProductos">   
        <div id="ContenedorBotones" class="col s12 m12 l3 xl3"> 
            <h5 class="centrar margenBotones">Categorias <b>Perifericos</b></h5>
            <div class="container">
                <div class="row">             
                    <div class="container-fluid col s12" id="categorias">                          
                    </div>
                    <div class="col s12"><hr>
                        <div id="BusquedaFiltrada" class="row">   
                            <h6 class="centrar"><b>Busqueda filtrada</b></h6>
                            <form method="post" id="search-form">            
                                <input class="hide" type="text" id="txtCategoria" name="txtCategoria" /> <!-- Aqui se guarda el nombre de la categoria --> 
                                <input class="hide" type="number" id="txtIdCategoria" name="txtIdCategoria" /> <!-- Aqui se guarda el id de la categoria --> 
                                <input class="hide" type="number" id="txtSeccion" name="txtSeccion" /> <!-- Aqui se guarda el id de la seccion a la que pertenecen las categorias -->  
                                
                                <div class="input-field col s12">
                                    <input id="search" type="number" name="search" required />
                                    <label for="search">Precio Menor</label>
                                </div>

                                <div class="input-field col s12 ">
                                    <input id="search2" type="number" name="search2" required />
                                    <label for="search2">Precio Mayor</label>
                                </div>
      
                                <div class="input-field col s12">
                                    <a onclick="busquedaFiltrada()" class="waves-effect waves-light btn tooltipped" data-tooltip="Buscar"><i class="material-icons right">find_in_page</i>Aplicar filtro</a>
                                </div>

                                <div class="col sm6">
                                    <a onclick="cargarDatos()" class="waves-effect waves-light btn"><i class="material-icons right">loop</i>Cargar datos</a>
                                </div>
                            </form>     
                        </div>         
                    </div>
                </div>    
            </div>   
        </div>
        <div id="ContendorTarjetas" class="col s12 m12 lg9 xl9">
            <div id="productos" class="row margen">  
            </div>  
        </div>
    </div>
</div>

<!-- Componente Modal para mostrar una caja de dialogo -->
<div id="save-modal" class="modal">
    <div class="modal-content">
        <h4 id="modal-title" class="center-align"></h4><br>
        <form method="post" id="save-form" enctype="multipart/form-data">
            <input class="hide" type="number" id="txtId" name="txtId" /> <!-- Aqui se guarda el id de la categoria seleccionada -->  
            <input class="hide" type="number" id="precio" name="precio" />
            <div class="row">
                <div id="imagen-producto" class="col m12 l5">                   
                    <div id="imagen" class="row"></div> <!-- Aqui se ingresa la imagen mediante el inner html desde js. -->  
                </div>            
                <div id="datos-producto" class="col m12 l7">
                    <div class="row"> <!-- Seccion de campos de texto. -->                                    
                        <h6 id="lblProducto"></h6>
                        <h6 id="lblMarca"></h6>
                        <h6 id="lblDescripcion"></h6>
                        <h6 id="lblPrecio"></h6>
                        <div id="cantidad-producto" class="row">
                            <div id="input-cantidad" class="input-field col s6"></div> <!-- Aqui se imprime el input numerico desde js. -->  
                            <div id="boton-producto" class="col s6">
                            <button type="submit" class="btn waves-effect waves-light blue tooltipped"
                                    data-tooltip="Agregar al carrito"><i
                                        class="material-icons">add_shopping_cart</i></button>                      
                                                  <style>
                                    #imagen-producto img{
                                        padding-left:70px;
                                    }

                                    #datos-producto h6{
                                        font-weight: bold;
                                        padding-top:5px;
                                        padding-bottom:5px;
                                    }

                                    #datos-producto {
                                        padding-left: 35px;
                                    }

                                    #boton-producto {
                                        padding-left: 35px;
                                        padding-top:20px;
                                    }

                                    #cantidad-producto {
                                        padding-top:30px;
                                    }
                                    </style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </form>
    </div>
</div>

<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('perifericos.js');
?>