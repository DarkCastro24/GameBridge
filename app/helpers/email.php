<?php
require_once __DIR__ . '/env.php';
// Incluir las clases de PHPMailer
require_once __DIR__ . '/../../libraries/PHPMailer-6.7.1/src/Exception.php';
require_once __DIR__ . '/../../libraries/PHPMailer-6.7.1/src/PHPMailer.php';
require_once __DIR__ . '/../../libraries/PHPMailer-6.7.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

    // Funcion para enviar el correo electronico al destino seleccionado utilizando PHPMailer
    public function enviarCorreo()
    {
        try {
            // Obtener credenciales SMTP desde variables de entorno
            $smtpHost = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
            $smtpPort = getenv('MAIL_PORT') ?: 587;
            $smtpUser = getenv('MAIL_USERNAME') ?: '';
            $smtpPass = getenv('MAIL_PASSWORD') ?: '';
            $smtpEncryption = getenv('MAIL_ENCRYPTION') ?: 'tls';
            $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: $smtpUser;
            $fromName = getenv('MAIL_FROM_NAME') ?: 'GameBridge';

            // Crear una nueva instancia de PHPMailer
            $mail = new PHPMailer(true);

            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUser;
            $mail->Password   = $smtpPass;
            $mail->SMTPSecure = ($smtpEncryption === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int) $smtpPort;
            $mail->CharSet    = 'UTF-8';

            // Remitente y destinatario
            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($this->correo);

            // Contenido del correo
            $mail->isHTML(false);
            $mail->Subject = $this->asunto;
            $mail->Body    = $this->mensaje;

            // Enviar el correo
            $mail->send();

            // Log opcional para depuración
            $logDir = __DIR__ . '/../../logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/emails.log';
            $content =
                "----- " . date('Y-m-d H:i:s') . " -----\n" .
                "TO: " . $this->correo . "\n" .
                "SUBJECT: " . $this->asunto . "\n" .
                "STATUS: Enviado correctamente\n\n";
            file_put_contents($logFile, $content, FILE_APPEND);

            return true;
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al enviar el correo electrónico: " . $e->getMessage();
            // Log del error
            $logDir = __DIR__ . '/../../logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/emails.log';
            $content =
                "----- " . date('Y-m-d H:i:s') . " -----\n" .
                "TO: " . $this->correo . "\n" .
                "SUBJECT: " . $this->asunto . "\n" .
                "STATUS: Error - " . $e->getMessage() . "\n\n";
            file_put_contents($logFile, $content, FILE_APPEND);
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
