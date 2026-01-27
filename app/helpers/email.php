<?php
$email = "";
$name = "";
$errors = array();

class Correo extends Validator
{
    // Declaración de atributos (propiedades).
    private $correo = null;
    private $mensaje = null;
    private $asunto = null;
    private $codigo = null;

    /*
    *   Métodos para asignar valores a los atributos.
    */
    public function setCodigo($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->codigo = $value;
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

    public function setMensaje($value)
    {
        if ($this->validateText($value)) {
            $this->mensaje = $value;
            return true;
        } else {
            return false;
        }
    }

    public function setAsunto($value)
    {
        if ($this->validateText($value)) {
            $this->asunto = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getCorreo()
    {
        return $this->correo;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function getAsunto()
    {
        return $this->asunto;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    // Funcion para enviar el correo electronico al destino seleccionado
    public function enviarCorreo()
    {
        try {
            // Detectar entorno local (XAMPP)
            $isLocal = (
                isset($_SERVER['SERVER_NAME']) &&
                in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1'])
            );

            // Cabeceras (mejoradas)
            $headers  = "From: GameBridge <botcastroll24@gmail.com>\r\n";
            $headers .= "Reply-To: botcastroll24@gmail.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if ($isLocal) {
                // En local, no enviar: guardar en un log para depuración
                $logDir = __DIR__ . '/../../logs';
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0777, true);
                }

                $logFile = $logDir . '/emails.log';
                $content =
                    "----- " . date('Y-m-d H:i:s') . " -----\n" .
                    "TO: " . $this->correo . "\n" .
                    "SUBJECT: " . $this->asunto . "\n" .
                    "MESSAGE:\n" . $this->mensaje . "\n\n";

                file_put_contents($logFile, $content, FILE_APPEND);

                // Opcional: para mostrarlo en pantalla o en alertas
                $_SESSION['last_email_debug'] = [
                    'to' => $this->correo,
                    'subject' => $this->asunto,
                    'message' => $this->mensaje
                ];

                return true;
            }

            // En producción (o servidor con SMTP configurado)
            if (mail($this->correo, $this->asunto, $this->mensaje, $headers)) {
                return true;
            } else {
                $_SESSION['error'] = "Error al enviar el correo electrónico";
                return false;
            }
        } catch (Throwable $e) {
            $_SESSION['error'] = "Error al enviar el correo electrónico: " . $e->getMessage();
            return false;
        }
    }

    // Metodo para actualizar el codigo de confirmacion de un usuario
    public function validarCorreo($table)
    {
        // Declaramos la sentencia que enviaremos a la base con el parametro del nombre de la tabla (dinamico)
        $sql = "SELECT correo_electronico from $table where correo_electronico = ?";
        // Enviamos los parametros
        $params = array($this->correo);
        return Database::getRow($sql, $params);
    }

    // Metodo para actualizar el codigo de confirmacion de un usuario
    public function validarCodigo($table)
    {
        // Declaramos la sentencia que enviaremos a la base con el parametro del nombre de la tabla (dinamico)
        $sql = "SELECT correo_electronico from $table where codigo_recu = ? and correo_electronico = ?";
        // Enviamos los parametros
        $params = array($this->codigo, $_SESSION['mail']);
        return Database::getRow($sql, $params);
    }

    // Metodo para actualizar el codigo de confirmacion de un usuario
    public function actualizarCodigo($table, $codigo)
    {
        // Declaramos la sentencia que enviaremos a la base con el parametro del nombre de la tabla (dinamico)
        $sql = "UPDATE $table set codigo_recu = ? where correo_electronico = ?";
        // Enviamos los parametros
        $params = array($codigo, $this->correo);
        return Database::executeRow($sql, $params);
    }


    public function validarCodigo2($table)
    {
        // Declaramos la sentencia que enviaremos a la base con el parametro del nombre de la tabla (dinamico)
        $sql = "SELECT correo_electronico from $table where codigo_recu = ? and correo_electronico = ?";
        // Enviamos los parametros
        $params = array($this->codigo, $_SESSION['correo_electronico']);
        return Database::getRow($sql, $params);
    }
}
