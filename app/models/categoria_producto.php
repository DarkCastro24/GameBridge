<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    //Metodo para cargar el combobox de categorias
    public function readAll()
    {
        $sql = 'SELECT idcategoria,categoria FROM categorias';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
