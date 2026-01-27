<?php

class Clientes extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombres = null;
    private $apellidos = null;
    private $estado = null;
    private $dui = null;
    private $correo = null;
    private $clave = null;
    private $fecha = null;
    private $intentos = null;



    //METODOS PARA ASIGNAR EL VALOR A LOS ATRIBUTOS
    public function setId($value)
    {
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

    public function setFecha($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setNombres($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->nombres = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setIntentos($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->intentos = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setApellidos($value)
    {
        if ($this->validateAlphanumeric($value, 1, 40)) {
            $this->apellidos = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setDui($value)
    {
        if ($this->validateDUI($value)) {
            $this->dui = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setClave($value)
    {
        if ($this->validatePassword($value)) {
            $this->clave = $value;
            return true;
        } else {
            return false;
        }
    }

    // METODOS GET PARA OBTENER EL VALOR DE LAS VARIABLES 
    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombres;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getApellido()
    {
        return $this->apellidos;
    }

    public function getDui()
    {
        return $this->dui;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getIntentos()
    {
        return $this->intentos;
    }

    // METODOS PARA REALIZAR LAS OPERACIONES SCRUD 

    //Metodo search de clientes
    public function busquedaFiltrada($value)
    {
        $sql = 'SELECT idCliente as id, e.estado as estado,nombres,apellidos,dui,correo_electronico
        FROM clientes c
        INNER JOIN estadoCliente e ON c.estado = e.idEstado
        WHERE nombres ILIKE ? OR apellidos ILIKE ?
        ORDER BY nombres';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }

    //Metodo para registrar a un cliente
    public function ingresarDatos()
    {
        $sql = 'INSERT INTO clientes(idcliente, estado, nombres, apellidos, dui, correo_electronico, clave, fecharegistro) 
        VALUES (default, 1, ?, ?, ?, ?, ?, default)';
        $params = array($this->nombres, $this->apellidos, $this->dui, $this->correo, $this->clave);
        return Database::executeRow($sql, $params);
    }

    //Metodo para cargar los registros de cliente
    public function cargarDatos()
    {
        $sql = "SELECT idCliente as id, e.estado as estado,nombres,apellidos,dui,correo_electronico, CONCAT(nombres,' ',apellidos) as nombre
        FROM clientes c
        INNER JOIN estadoCliente e ON c.estado = e.idEstado
        ORDER BY nombres";
        $params = null;
        return Database::getRows($sql, $params);
    }

    //Metodo para cargar las direcciones de un cliente
    public function cargarDatosParam($idCliente)
    {
        $sql = 'SELECT direccion as direccion,codigo_postal as codigo,telefono_fijo as telefono
        from direcciones d where cliente = ?';
        $params = array($idCliente);
        return Database::getRows($sql, $params);
    }

    //Metodo para cargar los datos de un cliente
    public function cargarFila()
    {
        $sql = 'SELECT idCliente as id, nombres, apellidos,dui,correo_electronico as correo,e.estado as estado,clave
        FROM clientes c
        INNER JOIN estadoCliente e ON c.estado = e.idEstado
        WHERE idCliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    //Metodo para actualizar los datos de un cliente
    public function actualizarDatos()
    {
        $sql = 'UPDATE clientes
        SET estado= ?,nombres=?,apellidos=?,dui=?,correo_electronico=?
        WHERE idcliente=?';
        $params = array($this->estado, $this->nombres, $this->apellidos, $this->dui, $this->correo, $this->id);
        return Database::executeRow($sql, $params);
    }

    //Metodo para eliminar un cliente
    public function eliminarDatos()
    {
        $sql = 'DELETE FROM clientes WHERE idCliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //espacio para el sitio publico
    public function registerClient()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO clientes(idcliente, estado, nombres, apellidos, dui, correo_electronico, clave, fecharegistro,fechaclave) 
        VALUES (default, 1, ?, ?, ?, ?, ?, default,default)';
        $params = array($this->nombres, $this->apellidos, $this->dui, $this->correo, $hash);
        return Database::executeRow($sql, $params);
    }

    //Metodo para obtener el correo del cliente para el log in
    public function checkUser($correo)
    {
        $sql = "SELECT idcliente, estado, nombres,fechaclave, intentos FROM clientes WHERE correo_electronico = ?";
        $params = array($correo);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['idcliente'];
            $this->estado = $data['estado'];
            $this->nombres = $data['nombres'];
            $this->fecha = $data['fechaclave'];
            $this->intentos = $data['intentos'];

            $this->correo = $correo;
            return true;
        } else {
            return false;
        }
    }

    // Funcion para verificar si el usuario esta activo requiere del parametro del nombre de usuario
    public function checkState($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT idcliente from clientes where correo_electronico = ? and estado = 1';
        $params = array($usuario);
        // Se compara si los datos ingresados coinciden con el resultado obtenido de la base de datos
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para desactivar un usuario requiere de parametro el nombre de usuario
    public function desactivateUser($user)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE clientes
        SET estado = 2
        WHERE correo_electronico = ?;';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($user);
        return Database::executeRow($sql, $params);
    }

    //Metodo para obtener la contraseña del cliente para el log in
    public function checkPassword($password)
    {
        $sql = 'SELECT clave FROM clientes WHERE idcliente = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        $this->clave = $password;

        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    //Metodo para cargar los datos del cliente
    public function readProfile()
    {
        $sql = 'SELECT idcliente, correo_electronico,nombres, apellidos, dui
                FROM clientes
                WHERE idcliente = ?';
        $params = array($_SESSION['idcliente']);
        return Database::getRow($sql, $params);
    }

    //Metodo para actualizar los datos de un cliente
    public function editProfile()
    {
        $sql = 'UPDATE clientes
                SET  correo_electronico = ?, nombres = ?, apellidos = ?, dui = ?
                WHERE idcliente = ?';
        $params = array($this->correo, $this->nombres, $this->apellidos, $this->dui, $_SESSION['idcliente']);
        return Database::executeRow($sql, $params);
    }

    //Metodo para cambiar la contraseña
    public function changePassword()
    {
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE clientes SET clave = ?, fechaclave=default WHERE idcliente = ?';
        $params = array($hash, $_SESSION['idcliente']);
        return Database::executeRow($sql, $params);
    }

    //Metodo para cargar las facturas de un cliente
    public function cargarFacturas()
    {
        $sql = "SELECT concat(c.Categoria,' ',p.Producto) as producto,cl.idcliente, d.PrecioUnitario,d.Cantidad
		from DetallePedidos d
        inner join Facturas f on d.pedido = f.IdFactura
		inner join Clientes cl on f.cliente = cl.idcliente
        inner join Productos p on d.Producto = p.IdProducto
        inner join Categorias c on p.Categoria = c.idCategoria
        where cl.idcliente = ? and f.estado=2 and f.fecha = current_date";
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }


    // Funcion para actualizar un usuario en la base de datos
    public function updatePassword()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE clientes set clave = ? , estado = 1, intentos=0, fechaclave=default where correo_electronico = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($hash, $this->correo);
        return Database::executeRow($sql, $params);
    }

    public function checkDate($fecha)
    {
        // Declaracion de la sentencia SQL 
        $sql = "SELECT diasClave(?)";
        $params = array($fecha);
        $data = Database::getRow($sql, $params);
        if ($data['diasclave'] < 90) {
            return true;
        } else {
            return false;
        }
    }


    public function intentosCliente($intentos)
    {
        $sql = 'UPDATE clientes set intentos = ? where correo_electronico = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($intentos, $this->correo);
        return Database::executeRow($sql, $params);
    }


    public function historialCliente()
    {
        $hash = php_uname();
        $sql = 'INSERT INTO historialCliente values (default,?,?,default)';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($this->id, $hash);
        return Database::executeRow($sql, $params);
    }


    public function readDevices()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT distinct sistema from historialCliente where cliente=? ';
        $params = array($_SESSION['idcliente']);
        return Database::getRows($sql, $params);
    }


    public function readHistory($device)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT hora from historialCliente where sistema=? order by hora desc limit 5  ';
        $params = array($device);
        return Database::getRows($sql, $params);
    }
}
