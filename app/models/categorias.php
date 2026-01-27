<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    //Metodo para cargar el estado del cliente
    public function readAll()
    {
        $sql = 'SELECT * FROM estadoCliente';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
