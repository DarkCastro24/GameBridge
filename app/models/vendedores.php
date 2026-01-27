<?php
/*
*	Clase para manejar la tabla usaurios de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    // Metodo para cargar los usuarios a asignar para la factura
    public function readAll()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idusuario, usuario FROM usuarios';
        $params = null;
        return Database::getRows($sql, $params);
    }
}
