<?php
/*
*	Clase para manejar las tablas pedidos y detalle_pedido de la base de datos. Es clase hija de Validator.
*/
class Pedidos extends Validator
{
    // Declaracion de atributos para pedidos
    private $id = null;
    private $id_pedido = null;
    private $id_detalle = null;
    private $cliente = null;
    private $producto = null;
    private $cantidad = null;
    private $precio = null;
    private $estado = null; 
    private $cantidadStock = null;

    // Declaracion de atributos para direcciones
    private $iddireccion=null;
    private $municipio=null;
    private $direccion=null;
    private $codigopostal=null;
    private $telefonofijo=null;
    private $cmbdireccion=null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */

    // Métodos para asignar el valor a los atributos
    public function setId($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdPedido($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id_pedido = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIdDetalle($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id_detalle = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCliente($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cliente = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setProducto($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->producto = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCantidad($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            return false;
        }
    }

    
    public function setCantidadStock($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cantidadStock = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setPrecio($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para retornar el valor los atributos de la clase
    public function getId()
    {
        return $this->id;
    }

    public function getIdPedido()
    {
        return $this->id_pedido;
    }

    // Método para verificar si existe un pedido en proceso para seguir comprando, de lo contrario se crea uno.
    public function startOrder()
    {
        $this->estado = 1;
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idfactura FROM facturas WHERE estado = ? AND cliente = ?';
        // Cargamos los parametros en un arreglo 
        $params = array($this->estado, $_SESSION['idcliente']);
        // Verificamos si existe una factura activa del cliente que inicio sesion
        if ($data = Database::getRow($sql, $params)) {
            $this->id_pedido = $data['idfactura'];
            return true;
        } else {
            // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
            $uuid = self::generateUUID();
            $sql = 'INSERT INTO facturas(uuid,estado,cliente,fecha,vendedor) VALUES(?,?, ?,current_date,12)';
            // Cargamos los parametros en un arreglo
            $params = array($uuid, $this->estado, $_SESSION['idcliente']);
            // Se obtiene el ultimo valor insertado en la llave primaria de la tabla pedidos.
            if ($this->id_pedido = Database::getLastRow($sql, $params)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // Método para cargar las direcciones de un cliente
    public function cargarDatosParam($idCliente)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT iddireccion,direccion,codigo_postal,telefono_fijo 
        from direcciones 
        where cliente = ?';
        $params = array($idCliente);
        return Database::getRows($sql, $params);
    }

    // Método para eliminar una direccion
    public function deleteRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'DELETE FROM direcciones 
        where iddireccion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para agregar un producto al carrito de compras.
    public function createDetail()
    {
        // Se realiza una subconsulta para obtener el precio del producto.
        $uuid = self::generateUUID();
        $sql = 'INSERT INTO detallepedidos(uuid, producto, preciounitario, cantidad, pedido) VALUES(?, ?, ?, ?, ?)';
        $params = array($uuid, $this->producto, $this->precio, $this->cantidad, $this->id_pedido);
        return Database::executeRow($sql, $params);
    }

    // Método para obtener los productos que se encuentran en el carrito de compras.
    public function readOrderDetail()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT iddetallefactura,pedido,p.producto,idproducto,d.cantidad,
        preciounitario,p.imagen
        from detallepedidos d
        INNER JOIN facturas f ON f.idFactura = d.pedido
        INNER JOIN productos p ON p.idProducto = d.producto
        WHERE idfactura = ? order by iddetallefactura';
        // Cargamos los parametros en un arreglo
        $params = array($this->id_pedido);
        return Database::getRows($sql,$params);
    }

    // Método para finalizar un pedido por parte del cliente.
    public function finishOrder()
    {
        // Se establece la zona horaria local para obtener la fecha del servidor.
        $this->estado = 2;
        $sql = 'UPDATE facturas
                SET estado = ?
                WHERE idfactura = ?';
        $params = array($this->estado, $_SESSION['idfactura']);
        return Database::executeRow($sql, $params);
    }

    // Método para actualizar la cantidad de un producto agregado al carrito de compras.
    public function updateDetail()
    {
        $sql = 'UPDATE detallepedidos
                SET cantidad = ?
                WHERE iddetallefactura = ? AND pedido = ?';
        $params = array($this->cantidad, $this->id_detalle, $_SESSION['idfactura']);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un producto que se encuentra en el carrito de compras.
    public function deleteDetail()
    {
        $sql = 'DELETE FROM detallepedidos
                WHERE iddetallefactura = ? AND pedido = ?';
        $params = array($this->id_detalle, $_SESSION['idfactura']);
        return Database::executeRow($sql, $params);
    }
    
    // Funciones para retornar el valor los atributos de la clase
    public function getIdDireccion()
    {
        return $this->iddireccion;
    }

    public function getTelefonoFijo()
    {
        return $this->telefonofijo;
    }

    public function getCodigoPostal()
    {
        return $this->codigopostal;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    //Métodos para validar y asignar valores de los atributos.

    public function setIdDireccion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->iddireccion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCodigoPostal($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->codigopostal = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefonoFijo($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->telefonofijo = $value;
            return true;
        } else {
            return false;
        }
    }
    

    public function setDireccion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 100)) {
            $this->direccion = $value;
            return true;
        } else {
            return false;
        }
    }
 
    public function setcmbDireccion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->cmbdireccion = $value;
            return true;
        } else {
            return false;
        }
    }

    // Método para crear direcciones
    public function crearDireccion()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'INSERT INTO direcciones(cliente, direccion, codigo_postal, telefono_fijo) VALUES(?, ?, ?, ?)';
        $params = array($_SESSION['idcliente'],$this->direccion, $this->codigopostal, $this->telefonofijo);
        return Database::executeRow($sql, $params);
    }

    // Metodo para fijar la direccion de envio
    public function updateDireccion()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'UPDATE  facturas set entrega=? where idfactura=?';
        $params = array($this->cmbdireccion,$_SESSION['idfactura']);
        return Database::executeRow($sql, $params);
    }

    //Metodo para cargar los datos de un producto
    public function readOne()
    {
        $sql = 'SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,descripcion, imagen ,cantidad 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        WHERE idProducto = ? AND p.estado = 1';
        $params = array($this->producto);
        return Database::getRow($sql, $params);
    }

    // Metodo para restar stock
    public function restarStock()
    {

        $newstock = $this->cantidadStock - $this->cantidad; {
            $sql = 'UPDATE productos set cantidad=? where idproducto=?';
            $params = array($newstock, $this->producto);
            return Database::executeRow($sql, $params);
        }
    }

    // Metodo para restaurar stock
    public function restoreStock()
    {

        $newstock = $this->cantidad + $this->cantidadStock; {
            $sql = 'UPDATE productos set cantidad=? where idproducto=?';
            $params = array($newstock, $this->producto);
            return Database::executeRow($sql, $params);
        }
    }

    //Metodo para actualizar stock
    public function updateStock($cantidad)
    {
        $sql = 'UPDATE productos set cantidad = ? where idproducto = ?';
        $params = array($cantidad, $this->producto);
        return Database::executeRow($sql,$params);
    }

    //Metodo para actualizar stock en el pedido
    public function updateOrderStock($cantidad)
    {
        $sql = 'UPDATE detallepedidos set cantidad = ? where iddetallefactura = ?  AND pedido = ?';
        $params = array($cantidad, $this->id_detalle,$_SESSION['idfactura']);
        return Database::executeRow($sql,$params);
    }

    //Metodo para cargar las direcciones
    public function readAll()
    {
        $sql = 'SELECT iddireccion, direccion as direccion,codigo_postal as codigo,telefono_fijo as telefono
        from direcciones d where cliente = ?';
        $params = array($_SESSION['idcliente']);
        return Database::getRows($sql, $params);
    }
    
    
}
