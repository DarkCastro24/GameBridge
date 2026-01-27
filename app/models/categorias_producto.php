<?php
/*
*   Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    private $idcategoria = null;
    private $categoria = null;
    private $seccion = null;
    private $descripcion = null;

    public function setIdcategoria($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->idcategoria = $value;
            return true;
        }
        return false;
    }

    public function setSeccion($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->seccion = $value;
            return true;
        }
        return false;
    }

    public function setCategoria($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->categoria = $value;
            return true;
        }
        return false;
    }

    public function setDescripcion($value)
    {
        if ($this->validateString($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        }
        return false;
    }

    public function getIdCategoria()
    {
        return $this->idcategoria;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function getSeccion()
    {
        return $this->seccion;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    // Cargar todas las categorias con su seccion
    public function readAll()
    {
        $sql = 'SELECT c.idcategoria, s.seccion, c.categoria
                FROM categorias c
                INNER JOIN secciones s ON c.seccion = s.idseccion
                ORDER BY s.seccion, c.categoria';
        return Database::getRows($sql, null);
    }

    // Buscar categorias por nombre de categoria o seccion
    public function searchRows($value)
    {
        $sql = 'SELECT c.idcategoria, s.seccion, c.categoria
                FROM categorias c
                INNER JOIN secciones s ON c.seccion = s.idseccion
                WHERE s.seccion ILIKE ? OR c.categoria ILIKE ?
                ORDER BY c.categoria';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    // Crear categoria
    public function createRow()
    {
        $sql = 'INSERT INTO categorias(categoria, seccion, descripcion)
            VALUES(?, ?, ?)';
        $params = array($this->categoria, $this->seccion, $this->descripcion);
        return Database::executeRow($sql, $params);
    }

    // Cargar una categoria
    public function readOne()
    {
        $sql = 'SELECT idcategoria, categoria, seccion
                FROM categorias
                WHERE idcategoria = ?';
        $params = array($this->idcategoria);
        return Database::getRow($sql, $params);
    }

    // Actualizar categoria
    public function updateRow()
    {
        $sql = 'UPDATE categorias
            SET categoria = ?, seccion = ?, descripcion = ?
            WHERE idcategoria = ?';
        $params = array($this->categoria, $this->seccion, $this->descripcion, $this->idcategoria);
        return Database::executeRow($sql, $params);
    }

    // Cargar productos de una categoria (solo activos)
    public function readProductosCategoria()
    {
        $sql = 'SELECT p.producto, p.precio, m.marca, p.cantidad
                FROM productos p
                INNER JOIN categorias c ON c.idcategoria = p.categoria
                INNER JOIN marcas m ON m.idmarca = p.marca
                WHERE c.idcategoria = ? AND p.estado = 1
                ORDER BY p.producto';
        $params = array($this->idcategoria);
        return Database::getRows($sql, $params);
    }

    // Cantidad de ventas por categoria (productos vendidos en facturas pagadas)
    public function readVentasCategorias()
    {
        $sql = 'SELECT SUM(d.cantidad) AS cantidad, p.producto, p.precio, m.marca
                FROM facturas f
                INNER JOIN detallepedidos d ON d.pedido = f.idfactura
                INNER JOIN productos p ON p.idproducto = d.producto
                INNER JOIN marcas m ON m.idmarca = p.marca
                WHERE f.estado = 2 AND p.categoria = ?
                GROUP BY p.producto, p.precio, m.marca
                ORDER BY cantidad DESC';
        $params = array($this->idcategoria);
        return Database::getRows($sql, $params);
    }

    // Cargar una categoria con su seccion (detalle)
    public function readCategoria()
    {
        $sql = 'SELECT c.idcategoria, s.seccion, c.categoria
                FROM categorias c
                INNER JOIN secciones s ON c.seccion = s.idseccion
                WHERE c.idcategoria = ?';
        $params = array($this->idcategoria);
        return Database::getRows($sql, $params);
    }

    // Cargar categorias por seccion
    public function readCategoriasSeccion()
    {
        $sql = 'SELECT c.idcategoria, s.seccion, c.categoria, s.idseccion
                FROM categorias c
                INNER JOIN secciones s ON c.seccion = s.idseccion
                WHERE s.idseccion = ?
                ORDER BY c.categoria';
        $params = array($this->seccion);
        return Database::getRows($sql, $params);
    }
}
