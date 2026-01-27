<?php

class Facturas extends Validator
{
    // Declaración de atributos (propiedades).
    private $id=null;
    private $usuario=null;
    private $estado=null;

    //METODOS PARA ASIGNAR LOS VALORES

    public function setId($value){

        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setUsuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->usuario = $value;
            return true;
        } else {
            return false;
        }
    }

    //Metodos GET

    public function getId()
    {
        return $this->id;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    //Metodo para cargar las facturas
    public function readAll()
    {
        $sql = "SELECT f.idfactura, Concat(c.nombres,' ',c.apellidos) as cliente ,e.estadofactura
                FROM Facturas f
                inner join Clientes c on f.Cliente=c.IdCliente 
                inner join EstadoFactura e on f.Estado=e.IdEstado
                order by f.IdFactura";
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Metodo para cargar los datos de una factura
    public function readOne()
    {
        $sql = 'SELECT idfactura , estado, vendedor
                FROM facturas  where idfactura = ? 
                ';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Metodo para actualizar los datos de una fatura
    public function updateRow()
    {
        $sql = 'UPDATE facturas 
                SET estado = ?, vendedor=?
                WHERE idfactura = ?';
        $params = array($this->estado,$this->usuario,$this->id);
        return Database::executeRow($sql, $params);

    }

    //Metodo search para facturas
    public function searchRows($value)
    {
        $sql = "SELECT f.idfactura, Concat(c.nombres,' ',c.apellidos) as cliente,e.estadofactura
                FROM Facturas f
                inner join Clientes c on f.Cliente=c.IdCliente 
                inner join EstadoFactura e on f.Estado=e.IdEstado
                where concat(c.nombres,' ',c.apellidos) ILIKE ?
                order by f.IdFactura";
        $params =  array("%$value%");;
        return Database::getRows($sql, $params);
    }

    public function cargarDatosParam($idFactura)
    {
        $sql = "SELECT concat(c.Categoria,' ',p.Producto) as producto, d.PrecioUnitario,d.Cantidad,
		d.PrecioUnitario*d.Cantidad as totalunitario from DetallePedidos d
        inner join Facturas f on d.pedido = f.IdFactura
        inner join Productos p on d.Producto = p.IdProducto
        inner join Categorias c on p.Categoria = c.idCategoria
        where f.idfactura = ?
        order by d.IdDetalleFactura";
        $params = array($idFactura);
        return Database::getRows($sql, $params);
    }

    public function getTotalPrice()
    {
        $sql = 'SELECT sum(preciounitario*cantidad)as total from detallepedidos where pedido=?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    


}