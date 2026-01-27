<?php
/*
*	Clase para manejar la tabla valoraciones de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    // Declaracion de atributos de la clase
    private $id = null;
    private $detalle = null;
    private $calificacion = null;
    private $comentario = null;
    private $estado = null;


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

    public function setDetalle($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->detalle = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCalificacion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 2)) {
            $this->calificacion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setComentario($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 250)) {
            $this->comentario = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para retornar los atributos de la clase
    public function getId()
    {
        return $this->id;
    }

    public function getDetalle()
    {
        return $this->detalle;
    }

    public function getCalificacion()
    {
        return $this->calificacion;
    }

    public function getComentario()
    {
        return $this->comentario;
    }


    // Metodo para ingresar una valoracion a la base de datos
    public function createRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'INSERT INTO valoraciones values (default,?,?,?,default,true);';
        $params = array($this->detalle, $this->calificacion, $this->comentario);
        return Database::executeRow($sql, $params);
    }

    // Metodo para cargar todas las valoraciones de un cliente
    public function readAll($idCliente)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT iddetallefactura as id, p.producto,preciounitario,d.cantidad,f.estado,p.imagen
        FROM detallepedidos d
        INNER JOIN facturas f ON f.idFactura = d.pedido
        INNER JOIN productos p ON p.idProducto = d.producto
        WHERE f.estado = 2 AND f.cliente = ?
        ORDER BY p.precio';
        $params = array($idCliente);
        return Database::getRows($sql, $params);
    }

    // Metodo para cargar los datos de una valoracion 
    public function readOne()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT calificacion_producto,comentario_producto,p.producto
        from valoraciones v
        INNER JOIN detallepedidos d ON d.iddetallefactura = v.id_detalle
        INNER JOIN productos p ON p.idProducto = d.producto 
        where id_detalle = ? and estado_comentario = true';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Metodo para actualizar los datos de una valoracion
    public function updateRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'UPDATE valoraciones set calificacion_producto = ? , comentario_producto = ? 
        where id_detalle = ?';
        $params = array($this->calificacion, $this->comentario, $this->detalle);
        return Database::executeRow($sql, $params);
    }

    // Metodo para eliminar una valoracion
    public function deleteRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'DELETE FROM valoraciones 
        where id_detalle = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
