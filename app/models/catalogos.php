<?php
/*
*   Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*   Versión ajustada a la estructura real:
*   categorias(idcategoria, categoria, descripcion, imagen)
*   Sin secciones.
*
*   Nota: Este modelo también incluye métodos para listar productos por categoría (según tu código legacy).
*/

class Categorias extends Validator
{
    // Propiedades.
    private $id = null;
    private $nombre = null;       // corresponde a categorias.categoria
    private $imagen = null;       // corresponde a categorias.imagen
    private $descripcion = null;  // corresponde a categorias.descripcion
    private $ruta = '../../../resources/img/categorias/';

    /*
    *   Métodos para asignar valores a los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        }
        return false;
    }

    public function setNombre($value)
    {
        // En tu BD: categoria varchar(30). Aquí permitimos hasta 50 como en tu legacy.
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre = $value;
            return true;
        }
        return false;
    }

    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagen = $this->getImageName();
            return true;
        }
        return false;
    }

    public function setDescripcion($value)
    {
        // En tu BD: descripcion varchar(150) NOT NULL.
        // Si viene vacío, lo rechazamos.
        if ($value && $this->validateString($value, 1, 150)) {
            $this->descripcion = $value;
            return true;
        }
        return false;
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
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

    /*
    *   CRUD de categorías (sin secciones)
    */

    // Búsqueda de categorías por nombre
    public function searchCategorias($value)
    {
        $sql = 'SELECT idcategoria AS id, categoria, descripcion, imagen
                FROM categorias
                WHERE categoria ILIKE ?
                ORDER BY categoria';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    // Crear categoría
    public function createRow()
    {
        $sql = 'INSERT INTO categorias(categoria, descripcion, imagen)
                VALUES(?, ?, ?)';
        $params = array($this->nombre, $this->descripcion, $this->imagen);
        return Database::executeRow($sql, $params);
    }

    // Listar todas las categorías
    public function readAll()
    {
        $sql = 'SELECT idcategoria AS id, categoria, descripcion, imagen
                FROM categorias
                ORDER BY categoria';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Leer una categoría por id
    public function readOneCategoria()
    {
        $sql = 'SELECT idcategoria AS id, categoria, descripcion, imagen
                FROM categorias
                WHERE idcategoria = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Actualizar una categoría
    public function updateRow($current_image)
    {
        // Si se sube nueva imagen, se borra la anterior; si no, se mantiene la actual.
        if ($this->imagen) {
            $this->deleteFile($this->getRuta(), $current_image);
        } else {
            $this->imagen = $current_image;
        }

        $sql = 'UPDATE categorias
                SET imagen = ?, categoria = ?, descripcion = ?
                WHERE idcategoria = ?';
        $params = array($this->imagen, $this->nombre, $this->descripcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Eliminar una categoría
    public function deleteRow()
    {
        $sql = 'DELETE FROM categorias
                WHERE idcategoria = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos legacy relacionados a productos por categoría
    *   Ajustados a tu esquema:
    *   productos(idproducto, categoria, estado, marca, producto, precio, descripcion, imagen, cantidad, ...)
    *   estadoproductos(idestado, estado)
    *   marcas(idmarca, marca)
    */

    // Buscar productos por nombre de categoría + rango de precio (sin seccion)
    public function searchRows($categoriaNombre, $minPrecio, $maxPrecio)
    {
        $sql = 'SELECT p.idproducto AS id, c.categoria, e.estado, m.marca, p.producto, p.precio, p.descripcion, p.imagen
                FROM productos p
                INNER JOIN categorias c ON c.idcategoria = p.categoria
                INNER JOIN estadoproductos e ON e.idestado = p.estado
                INNER JOIN marcas m ON m.idmarca = p.marca
                WHERE c.categoria = ? AND p.estado = 1 AND p.precio BETWEEN ? AND ?
                ORDER BY p.precio';
        $params = array($categoriaNombre, $minPrecio, $maxPrecio);
        return Database::getRows($sql, $params);
    }

    // Cargar productos de una categoría por idcategoria
    public function readProductosCategoria()
    {
        $sql = 'SELECT p.idproducto AS id, c.categoria AS cat, e.estado, m.marca, p.producto, p.precio, p.descripcion, p.imagen, c.idcategoria
                FROM productos p
                INNER JOIN categorias c ON c.idcategoria = p.categoria
                INNER JOIN estadoproductos e ON e.idestado = p.estado
                INNER JOIN marcas m ON m.idmarca = p.marca
                WHERE c.idcategoria = ? AND p.estado = 1
                ORDER BY p.producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

    // Cargar un producto seleccionado (legacy: usa $this->id como idproducto)
    public function readOne()
    {
        $sql = 'SELECT p.idproducto AS id, c.categoria, e.estado, m.marca, p.producto, p.precio, p.descripcion, p.imagen, p.cantidad
                FROM productos p
                INNER JOIN categorias c ON c.idcategoria = p.categoria
                INNER JOIN estadoproductos e ON e.idestado = p.estado
                INNER JOIN marcas m ON m.idmarca = p.marca
                WHERE p.idproducto = ? AND p.estado = 1';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
}
