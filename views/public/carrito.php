<?php
require_once('../../app/helpers/public_page.php');
Public_Page::headerTemplate('Carrito de compras', 'cart');
?>


<div class="container">
    <div class="row">
        <div class="col xs12 s12 m12 l8 xl7">
            <br>
            <h4 class="center-align">Tu carrito de compras</h4>
            <hr>
            <br>
            <div id="detalle" class="container-fluid ">
                <table class="espacioTabla responsive-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-rows">
                    </tbody>
                </table>

            </div>
            <div class="row right-align">
                <p>TOTAL A PAGAR (US$) <b id="pago"></b></p>
            </div>
        </div>
        <div class="col xs12 s12 m12 l4 xl5">
            <div id="Totales" class="container">
                <br>
                <br>
                <br>
                <div class="row">
                    <button class="btn botonModal" onclick="finishOrder()">Finalizar compra<i class="material-icons right">attach_money</i></button>
                </div>
                <div id="opcionesEntrega" class="row">
                    <!-- Lugar donde se ubican los botones para abrir los modals -->
                    <div class="row">

                        <div class="col s5">
                            <p>Direcciones de envio</p>
                        </div>
                        <div id="direcciones" class="col s7">
                            <i class="small buttonpointer material-icons tooltipped" data-tooltip="Agregar direccion" onclick="openCreateDialog()">add_circle</i>
                            <i class="small buttonpointer material-icons tooltipped" data-tooltip="Ver direcciones" onclick="openAddressDialog()">visibility</i>
                        </div>
                    </div>

                    <div id="DireccionSeleccionada" class="row">
                        <div class="col">
                            <button class="btn botonModal" onclick="openFixDialog()">Fijar dirección<i class="material-icons right">location_on</i></button>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Componente Modal para crear una direccion -->
<div id="save-modal" class="modal">
    <div class="modal-content">
        <h4 class="centrar">Crear dirección</h4>
        <form method="post" id="address-form2">
            <div class="row">
                <input type="number" id="iddireccion" name="iddireccion" class="hide" />
                <div class="col s12 md6 lg6 xl6">
                    <div class="container-fluid separacionCampos">
                        <input placeholder="Dirección (Por favor, no agregar comas )" id="direccion" name="direccion" type="text" class="validate" required>
                    </div>

                </div>
                <div class="col s12 md6 lg6 xl6">
                    <div class="container-fluid separacionCampos">
                        <input placeholder="Código postal" id="codigopostal" name="codigopostal" type="text" class="validate" required>

                    </div>
                </div>
                <div class="col s12 md12 lg12 xl12">
                    <div class="container-fluid separacionCampos">
                        <input placeholder="Linea fija" id="telefono" name="telefono" type="text" class="validate" required>

                    </div>
                </div>
            </div>
            <div class="container centrarBotones separacionCampos">
                <!-- Botón para crear la dirección -->
                <button class="btn waves-effect waves-light botoncrearDireccion" type="submit" name="action">Agregar
                    direccion<i class="material-icons right">add</i></button>
            </div>
        </form>


    </div>

</div>

<!-- Componente Modal para mostrar una caja de dialogo -->
<div id="address-modal" class="modal">
    <div class="modal-content">
        <h4 id="modal-title" class="center-align">Direcciones del cliente</h4><br>
        <form method="post" id="address-form" enctype="multipart/form-data">
            <!-- Campo oculto usado como parametro para mostrar datos-->
            <input class="hide" type="number" id="txtIdx" name="txtIdx" />
        </form>
        <!-- Tabla para mostrar las direcciones de un cliente -->
        <table class="striped centered responsive-table" id="miTabla">
            <thead>
                <tr id="tableHeader">
                    <th>Direccion</th>
                    <th>Codigo</th>
                    <th>Telefono</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="tbody-rows2">
            </tbody>
        </table>
        <br>
        <div class="row">
            <div class="col s12  colconfig">
                <center><a class="waves-effect btn deleteButton2 modal-close" href="#!"><i class="material-icons left">clear</i>Salir
                    </a></center>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- Componente Modal para mostrar una caja de dialogo -->
<div id="item-modal" class="modal">
    <div class="modal-content">
        <!-- Título para la caja de dialogo -->
        <h5 id="modal-title" class="center-align">Cambiar cantidad</h5>
        <!-- Formulario para cambiar la cantidad de producto -->
        <form method="post" id="item-form">
            <!-- Campos ocultos para las operaciones de actualizar el stock o eliminar el producto del pedido -->
            <input type="number" id="id_detalle" name="id_detalle" class="hide" />
            <input type="number" id="idproducto" name="idproducto" class="hide" />
            <input type="number" id="stockPedido" name="stockPedido" class="hide" />
            <input type="number" id="stockBodega" name="stockBodega" class="hide" />
            <input type="number" id="stockReal" name="stockReal" class="hide" />
            <input type="number" id="txtCantidad2" name="txtCantidad2" class="hide" />
            <label class="hide" id="lblCantidadMaterial"></label>

            <!-- Campo para definir la cantidad que se desea actualizar del stock -->
            <div class="row">
                <div class="input-field col s12 m4 offset-m4">
                    <input type="number" id="cantidad_producto" name="cantidad_producto" min="1" class="validate" required />
                    <label for="cantidad_producto">Cantidad</label>
                </div>
            </div>
            <!-- Boton para actualizar la cantidad del producto o cancelar la operacion -->
            <div class="row center-align">
                <div class="col s12 m4 offset-m4">
                    <button type="submit" class="btn waves-effect blue tooltipped" data-tooltip="Guardar"><i class="material-icons">save</i></button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Componente modal para fijar direccion de destino -->
<div id="change-modal" class="modal">
    <div class="modal-content">
        <h4 id="modal-title" class="center-align"></h4>
        <form method="post" id="fix-form">
            <h4 class="centrar">Selecciona una dirección</h4>
            <!-- Campos ocultos usados para la actualizacion -->
            <input class="hide" type="number" id="txtId" name="txtId" />
            <div class="row">
                <!-- Select para fijar la direccion del pedido -->
                <div class="input-field col l12 s12 m12">
                    <select id="cmbDireccion" name="cmbDireccion">
                    </select>
                    <label>Direccion</label>
                </div>
            </div>
            <div class="container centrarBotones separacionCampos">
                <!-- Boton para fijar la dirección de destino del pedido-->
                <button class="btn waves-effect waves-light botoncrearDireccion" type="submit" name="action">Fijar
                    dirección
                    <i class="material-icons right">location_on</i></button>
            </div>
        </form>
    </div>
</div>


<?php
// Se imprime la plantilla del pie enviando el nombre del controlador para la página web.
Public_Page::footerTemplate('carrito.js');
?>