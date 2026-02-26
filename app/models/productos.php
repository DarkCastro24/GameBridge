<?php
/*
*	Clase para manejar la tabla productos de la base de datos. Es clase hija de Validator.
*/
class Productos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $categoria = null;
    private $estado = null;
    private $marca = null;
    private $producto = null;
    private $precio = null;
    private $descripcion = null;
    private $imagen = null;
    private $ruta = '../../../resources/img/productos/';

    /*
    *   Métodos para asignar valores a los atributos.
    */
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

    public function setCategoria($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->categoria = $value;
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

    public function setMarca($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->marca = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setProducto($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->producto = $value;
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

    public function setDescripcion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setImagen($file)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagen = $this->getImageName();
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getMarca()
    {
        return $this->marca;
    }

    public function getProducto()
    {
        return $this->producto;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getRuta()
    {
        return $this->ruta;
    }

    // Funcion para realizar la busqueda filtrada  
    public function searchRows($value)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,descripcion, imagen 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        WHERE producto ILIKE ? OR m.marca ILIKE ?
        ORDER BY c.categoria';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // Funcion para ingresar un producto en la base de datos 
    public function createRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $uuid = self::generateUUID();
        $sql = 'INSERT INTO productos(idproducto, uuid, categoria, estado, marca, producto, precio, descripcion, imagen,cantidad)
            VALUES (default, ?, ?, ?, ?, ?, ?, ?, ?,default);';
        // Envio de parametros
        $params = array($uuid, $this->categoria, $this->estado, $this->marca, $this->producto, $this->precio, $this->descripcion,  $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar todos los datos de la tabla productos
    public function readAll()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,p.descripcion, p.imagen, cantidad 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        order by c.categoria';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar los datos de un producto en especifico
    public function readOne()
    {
        // Sentencia SQL
        $sql = 'SELECT idProducto as id,c.categoria,e.estado,m.marca,producto,precio,p.descripcion, p.imagen, cantidad 
        FROM productos p
        INNER JOIN categorias c ON c.idCategoria = p.Categoria
        INNER JOIN estadoProductos e ON e.idEstado = p.estado
        INNER JOIN marcas m ON m.idMarca = p.marca
        WHERE idProducto = ?';
        // Envio de parametros
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para actualizar un registro 
    public function updateRow($current_image)
    {
        // Verificamos si existe una imagen en la base de datos
        if ($this->imagen) {
            $this->deleteFile($this->getRuta(), $current_image);
        } else {
            $this->imagen = $current_image;
        }
        // Sentencia SQL
        $sql = 'UPDATE productos
            SET  estado = ?, producto = ? , precio = ?, descripcion = ?, imagen = ?, marca = ?, categoria = ? 
            WHERE idProducto = ?';
        // Envio de parametros
        $params = array($this->estado, $this->producto, $this->precio, $this->descripcion, $this->imagen, $this->marca, $this->categoria, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para eliminar un registro de la base
    public function deleteRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'DELETE FROM productos WHERE idProducto = ?';
        // Envio de parametros
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Funciones para la generacion de graficos 
    */

    // Funcion de grafica de productos mas vendidos
    public function ventasProductos()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT p.producto, SUM(d.cantidad) cantidad
		from facturas f
		INNER JOIN estadofactura ef ON ef.idestado = f.estado
		INNER JOIN detallepedidos d ON d.pedido = f.idfactura 
		INNER JOIN productos p ON p.idProducto = d.producto
		where f.estado = 2
		group by p.producto order by cantidad DESC';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para grafica de los clientes con mas compras del sistema
    public function ventasClientes()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT c.correo_electronico, c.nombres || ' ' || c.apellidos as nombre,c.dui,COUNT(idcliente) cantidad
        from facturas f
        INNER JOIN clientes c ON c.idCliente = f.cliente
        where f.estado = 2
        group by c.correo_electronico,c.nombres,c.apellidos,c.dui
        order by cantidad DESC";
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para reporte de productos productos mas vendidos incluyendo su categoria
    public function ventasCategorias()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT c.categoria, SUM(d.cantidad) cantidad, p.producto,m.marca
		FROM facturas f
		INNER JOIN estadofactura ef ON ef.idestado = f.estado
		INNER JOIN detallepedidos d ON d.pedido = f.idfactura 
		INNER JOIN productos p ON p.idProducto = d.producto
		INNER JOIN marcas m ON m.idMarca = p.marca
		INNER JOIN categorias c ON c.idcategoria = p.categoria
		WHERE f.estado = 2
		GROUP BY c.categoria, p.producto,m.marca order by cantidad DESC';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para grafica la cantidad de productos vendidos cada dia
    public function ventasFechas()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT f.fecha, SUM(d.cantidad) cantidad
        from facturas f
		INNER JOIN detallepedidos d ON d.pedido = f.idfactura
        where f.estado = 2
        group by f.fecha
        order by f.fecha ASC';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para grafica de las marcas con mas productos vendidos
    public function ventasMarcas()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT m.marca, SUM(d.cantidad) cantidad
        from facturas f
        INNER JOIN detallepedidos d ON d.pedido = f.idfactura 
        INNER JOIN productos p ON p.idProducto = d.producto
        INNER JOIN marcas m ON m.idmarca = p.marca
        where f.estado = 2
        group by m.marca order by cantidad DESC';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    /*
    *   Funciones para la generacion de reportes 
    */

    // Funcion para reporte de los clientes con mas productos adquiridos dentro del sistema
    public function comprasClientes()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = "SELECT c.correo_electronico, c.nombres || ' ' || c.apellidos as nombre,c.dui,SUM(de.cantidad) cantidad
        from facturas f
        INNER JOIN clientes c ON c.idCliente = f.cliente
		INNER JOIN detallepedidos de ON de.pedido = f.idFactura
        where f.estado = 2
        group by c.correo_electronico,c.nombres,c.apellidos,c.dui
        order by cantidad DESC";
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para grafica de venta de productos por categoria
    public function categoriasVentas()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT c.categoria, SUM(d.cantidad) cantidad
		FROM facturas f
		INNER JOIN detallepedidos d ON d.pedido = f.idfactura 
		INNER JOIN productos p ON p.idProducto = d.producto
		INNER JOIN categorias c ON c.idcategoria = p.categoria
		WHERE f.estado = 2
		GROUP BY c.categoria order by cantidad DESC';
        // Envio de parametros
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Método para obtener los TOP 4 productos más vendidos
    public function getTopSold()
    {
        $sql = 'SELECT p.idproducto AS id, p.producto, p.precio, p.imagen, 
                        COALESCE(SUM(d.cantidad), 0) AS cantidadVendida,
                        m.marca, c.categoria
                FROM productos p
                LEFT JOIN detallepedidos d ON d.producto = p.idproducto
                LEFT JOIN facturas f ON f.idfactura = d.pedido AND f.estado = 2
                INNER JOIN marcas m ON m.idmarca = p.marca
                INNER JOIN categorias c ON c.idcategoria = p.categoria
                WHERE p.estado = 1
                GROUP BY p.idproducto, p.producto, p.precio, p.imagen, m.marca, c.categoria
                ORDER BY cantidadVendida DESC, p.producto ASC
                LIMIT 4';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
