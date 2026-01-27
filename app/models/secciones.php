<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Secciones extends Validator
{
    // Metodo para cargar el combobox de secciones
    public function readAll()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT * FROM secciones';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
