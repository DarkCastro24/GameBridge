<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    //Metodo para cargar los estados de los usuarios
    public function readAll()
    {
        $sql = 'SELECT * FROM estadousuarios';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
