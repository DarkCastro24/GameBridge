<?php

class Clientes extends Validator
{
    // Declaración de atributos (propiedades).
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

    public function setEstado($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateBoolean($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    // METODOS GET PARA OBTENER EL VALOR DE LAS VARIABLES 
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

    public function getEstado()
    {
        return $this->estado;
    }

    // Funcion para realizar busqueda filtrada en el sistema
    public function busquedaFiltrada($value)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT id_valoracion as id,c.nombres as nombre,c.apellidos as apellido,p.producto as producto,p.imagen as imagen,
        calificacion_producto as calificacion, comentario_producto as comentario,estado_comentario as estado 
        from valoraciones v
        INNER JOIN detallepedidos d ON d.idDetallefactura = v.id_Detalle
        INNER JOIN productos p ON p.idProducto = d.producto
        INNER JOIN facturas pe ON pe.idFactura = d.pedido
        INNER JOIN clientes c ON c.idCliente = pe.cliente
        WHERE c.nombres ILIKE ? OR c.apellidos ILIKE ? OR p.producto ILIKE ?
        order by id_valoracion';
        // Enviamos los parametros como arreglo 
        $params = array("%$value%", "%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // Funcion para registrar una valoracion en la base de datos
    public function ingresarDatos()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'INSERT INTO valoraciones(id_valoracion, id_detalle, calificacion_producto, comentario_producto, fecha_comentario, estado_comentario)
        VALUES (default, ?, ?, ?, default, ?);';
        $params = array($this->detalle, $this->calificacion, $this->comentario, $this->estado);
        return Database::executeRow($sql, $params);
    }
    
    // Funcion para cargar los datos de la tabla valoraciones
    public function cargarDatos()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT id_valoracion as id,c.nombres as nombre,c.apellidos as apellido,p.producto as producto,p.imagen as imagen,
        calificacion_producto as calificacion, comentario_producto as comentario,estado_comentario as estado 
        from valoraciones v
        INNER JOIN detallepedidos d ON d.idDetallefactura = v.id_Detalle
        INNER JOIN productos p ON p.idProducto = d.producto
        INNER JOIN facturas pe ON pe.idFactura = d.pedido
        INNER JOIN clientes c ON c.idCliente = pe.cliente
        order by id_valoracion';
        $params = null;
        return Database::getRows($sql, $params);
    }
    
    // Funcion para cargar los datos de un registro en especifico
    public function cargarFila()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT id_valoracion, id_detalle, calificacion_producto as calificacion, 
        comentario_producto as comentario, estado_comentario as estado
        FROM valoraciones
        WHERE id_valoracion = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para actualizar los datos de una valoracion
    public function actualizarDatos()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = ' UPDATE valoraciones
        SET calificacion_producto= ?, comentario_producto = ?, estado_comentario = ?
        WHERE id_valoracion = ?';
        $params = array($this->calificacion, $this->comentario, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para eliminar una valoracion de la base de datos
    public function eliminarDatos()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'DELETE FROM valoraciones WHERE id_valoracion = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
