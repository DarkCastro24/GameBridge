<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos. Es clase hija de Validator.
*/
class Usuarios extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombres = null;
    private $apellidos = null;
    private $correo = null;
    private $alias = null;
    private $clave = null;
    private $tipo = null;
    private $estado = null;
    private $fecha = null;
    private $accion = null;
    private $intentos = null;
    private $devices = null;
    private $telefono = null;
    private $dui = null;


    // Métodos para asignar el valor a los atributos
    public function setId($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
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

    public function setCorreo($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAlias($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->alias = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAccion($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->accion = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTelefono($value)
    {
        if ($this->validatePhone($value)) {
            $this->telefono = $value;
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

    public function setClave($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validatePassword($value)) {
            $this->clave = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setEstado($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->estado = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setTipo($value)
    {
        // Validamos el tipo de dato del valor ingresado
        if ($this->validateNaturalNumber($value)) {
            $this->tipo = $value;
            return true;
        } else {
            return false;
        }
    }

    // Funciones para retornar el valor los atributos de la clase
    public function getId()
    {
        return $this->id;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getClave()
    {
        return $this->clave;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getIntentos()
    {
        return $this->intentos;
    }

    // Funciones verificar si el usuario ingresado existe
    public function checkUser($alias)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idusuario,tipo,usuario,fechaclave,intentos,correo_electronico FROM usuarios WHERE usuario = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['idusuario'];
            $this->tipo = $data['tipo'];
            $this->fecha = $data['fechaclave'];
            $this->intentos = $data['intentos'];
            $this->correo = $data['correo_electronico'];
            $this->alias = $alias;
            $_SESSION['usuario'] = $alias;
            return true;
        } else {
            return false;
        }
    }

    // Funciones verificar si el usuario ingresado existe
    public function obtenerUsuario($correo)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT usuario FROM usuarios WHERE correo_electronico = ?';
        $params = array($correo);
        if ($data = Database::getRow($sql, $params)) {
            $_SESSION['usuario'] = $data['usuario'];
            $_SESSION['clave'] = 'Kstro@02';
            return true;
        } else {
            return false;
        }
    }

    // Funciones verificar si la contraseña es correcta
    public function checkPassword($password)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT clave FROM usuarios WHERE idusuario = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        $_SESSION['clave'] = $password;
        if (password_verify($password, $data['clave'])) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para verificar si el usuario esta activo requiere del parametro del nombre de usuario
    public function checkState($usuario)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'SELECT idusuario from usuarios where usuario = ? and estado = 1';
        $params = array($usuario);
        // Se compara si los datos ingresados coinciden con el resultado obtenido de la base de datos
        if ($data = Database::getRow($sql, $params)) {
            return true;
        } else {
            return false;
        }
    }

    // Funcion para verificar si el usuario necesita cambio de contraseña
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

    // Funcion para desactivar un usuario requiere de parametro el nombre de usuario
    public function desactivateUser($user)
    {
        // Declaracion de la sentencia SQL 
        $sql = 'UPDATE usuarios
        SET estado = 2
        WHERE usuario = ?;';
        // Creacion de arreglo para almacenar los parametros que se enviaran a la clase database
        $params = array($user);
        return Database::executeRow($sql, $params);
    }

    // Funciones cambiar la clave del usuario
    public function changePassword()
    {
        // Encriptacion de la contraseña
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'UPDATE usuarios SET clave = ? WHERE idusuario = ?';
        $params = array($hash, $_SESSION['idusuario']);
        return Database::executeRow($sql, $params);
    }


    //funcion para ver dispositivos

    public function readDevices()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT distinct sistema from historialusuario where usuario=? ';
        $params = array($_SESSION['idusuario']);
        return Database::getRows($sql, $params);
    }


    public function readHistory($device)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT hora from historialusuario where sistema=? order by hora desc limit 5  ';
        $params = array($device);
        return Database::getRows($sql, $params);
    }


    // Funciones para cargar los datos un cliente que inicio sesion
    public function readProfile()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idusuario, correo_electronico, usuario
                FROM usuarios
                WHERE idusuario = ?';
        $params = array($_SESSION['idusuario']);
        return Database::getRow($sql, $params);
    }

    // Funciones para editar los datos un cliente que inicio sesion
    public function editProfile()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'UPDATE usuarios
                SET  correo_electronico = ?, usuario = ?
                WHERE idusuario = ?';
        $params = array($this->correo, $this->alias, $_SESSION['idusuario']);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */

    // Funcion para realizar busqueda filtrada en el sistema
    public function searchRows($value)
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT u.idusuario, e.estado, u.usuario, u.correo_electronico
                FROM usuarios u, estadousuarios e where u.estado=e.idestado
                and usuario ILIKE ?
                ORDER BY usuario';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    // Funcion para registrar un usuario en la base de datos
    public function createRow()
    {
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $uuid = self::generateUUID();
        $sql = 'INSERT INTO usuarios(uuid, tipo, usuario, clave, correo_electronico, telefono, dui) VALUES (?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $uuid,
            1,                  
            $this->alias,
            $hash,
            $this->correo,
            $this->telefono,
            $this->dui
        );
        return Database::executeRow($sql, $params);
    }


    // Funcion para registrar un usuario en la base de datos
    public function historialUsuario()
    {
        $hash = php_uname();
        $sql = 'INSERT INTO historialUsuario values (default,?,?,default)';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($this->id, $hash);
        return Database::executeRow($sql, $params);
    }

    // Funcion para actualizar los intentos de un usuario
    public function intentosUsuario($intentos)
    {
        $sql = 'UPDATE usuarios set intentos = ? where usuario = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($intentos, $this->alias);
        return Database::executeRow($sql, $params);
    }

    // Funcion para registrar un usuario en la base de datos
    public function addUser()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $uuid = self::generateUUID();
        $sql = 'INSERT INTO usuarios(uuid, tipo, estado, usuario, clave, correo_electronico)
                VALUES(?, ?, ?, ?, ?, ?)';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($uuid, $this->tipo, $this->estado, $this->alias, $hash, $this->correo);
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar todos los usuarios de la base de datos
    public function readAll()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idusuario, estado,usuario, correo_electronico
                FROM usuarios
                ORDER BY usuario';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar todos tipos de usuarios de la base de datos
    public function readAll2()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idtipo, tipousuario
        FROM tipousuarios order by tipousuario ';
        $params = null;
        return Database::getRows($sql, $params);
    }

    // Funcion para cargar los datos de un usuario en especifico
    public function readOne()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT idusuario, tipo , estado, usuario, correo_electronico
                FROM usuarios  where idusuario = ? ';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Funcion para actualizar un usuario en la base de datos
    public function updateRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'UPDATE usuarios 
                SET correo_electronico = ?, tipo=?, estado=?
                WHERE idusuario = ?';
        $params = array($this->correo, $this->tipo, $this->estado, $this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para actualizar un usuario en la base de datos
    public function updatePassword()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE usuarios set clave = ? , estado = 1, fechaClave = default where usuario = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($hash, $this->alias);
        return Database::executeRow($sql, $params);
    }

    // Funcion para eliminar un usuario en la base de datos
    public function deleteRow()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'DELETE FROM usuarios
                WHERE idusuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Funcion para cargar registros de un tipo de usuario en especifico
    public function readUsuariosTipo()
    {
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base
        $sql = 'SELECT u.idusuario, e.estado,t.tipousuario, t.idtipo,u.usuario, u.correo_electronico
                FROM usuarios u, estadousuarios e, tipousuarios t where u.estado=e.idestado and u.tipo=t.idtipo
                and u.tipo=?';
        $params = array($this->tipo);
        return Database::getRows($sql, $params);
    }

    // Funcion para actualizar un usuario en la base de datos
    public function updatePass()
    {
        // Se encripta la clave por medio del algoritmo bcrypt que genera un string de 60 caracteres.
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        $sql = 'UPDATE usuarios set clave = ? , estado = 1, intentos = 0, fechaClave = default where correo_electronico = ?';
        // Creamos la sentencia SQL que contiene la consulta que mandaremos a la base        
        $params = array($hash, $this->correo);
        return Database::executeRow($sql, $params);
    }
}
