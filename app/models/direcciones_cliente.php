<?php
/*
*	Clase para manejar la tabla categorias de la base de datos. Es clase hija de Validator.
*/
class Categorias extends Validator
{
    //metodo para cargar las direcciones del cliente que ha iniciado sesion
    public function readDirecciones()
    {
        $sql = 'SELECT iddireccion,direccion from direcciones where cliente=?';
        $params = array($_SESSION['idcliente']);
        return Database::getRows($sql, $params);
    }
}
