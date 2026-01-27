<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    // Funcion para cargar los tipos de usuario de la base de datos
    public function readAll()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT * FROM tipousuarios';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
