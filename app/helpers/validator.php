<?php
/*
*	Clase para validar todos los datos de entrada del lado del servidor.
*   Es clase padre de los modelos porque los datos se validan en los métodos setter.
*/
class Validator
{
    // Propiedades para manejar la validación de archivos de imagen.
    private $passwordError = null;
    private $imageError = null;
    private $imageName = null;
    private $targetWidth = null;
    private $targetHeight = null;

    /*
    *   Método para generar un UUID v4 (Universally Unique Identifier).
    *   Genera un identificador único de 36 caracteres con formato: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
    *
    *   Retorno: string con el UUID generado.
    */
    public static function generateUUID()
    {
        $data = random_bytes(16);
        // Establecer la versión 4 (bits 12-15 del byte 6)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Establecer la variante RFC 4122 (bits 6-7 del byte 8)
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /*
    *   Método para obtener el error al validar una imagen.
    */
    public function getPasswordError()
    {
        return $this->passwordError;
    }

    /*
    *   Método para obtener el nombre del archivo de la imagen validada previamente.
    */
    public function getImageName()
    {
        return $this->imageName;
    }

    /*
    *   Método para obtener el error al validar una imagen.
    */
    public function getImageError()
    {
        return $this->imageError;
    }

    /*
    *   Método para sanear todos los campos de un formulario (quitar los espacios en blanco al principio y al final).
    *
    *   Parámetros: $fields (arreglo con los campos del formulario).
    *   
    *   Retorno: arreglo con los campos saneados del formulario.
    */
    public function validateForm($fields)
    {
        foreach ($fields as $index => $value) {
            $value = strip_tags(trim($value));
            $fields[$index] = $value;
        }
        return $fields;
    }

    /*
    *   Método para validar un numero natural como por ejemplo llave primaria, llave foránea, entre otros.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateNaturalNumber($value)
    {
        // Se verifica que el valor sea un número entero mayor o igual a uno.
        if (filter_var($value, FILTER_VALIDATE_INT, array('min_range' => 1))) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un archivo de imagen.
    *
    *   Parámetros: $file (archivo de un formulario), $maxWidth (ancho máximo para la imagen) y $maxHeigth (alto máximo para la imagen).
    *   
    *   Retorno: booleano (true si el archivo es correcto o false en caso contrario).
    */
    public function validateImageFile($file, $targetWidth, $targetHeight)
    {
        // Se verifica si el archivo existe, de lo contrario se establece un número de error.
        if ($file) {
            // Se comprueba si el archivo tiene un tamaño menor o igual a 2MB, de lo contrario se establece un número de error.
            if ($file['size'] <= 2097152) {
                // Se obtienen las dimensiones de la imagen y su tipo.
                list($width, $height, $type) = getimagesize($file['tmp_name']);
                // Se comprueba si el tipo de imagen es permitido (1 - GIF, 2 - JPG y 3 - PNG), de lo contrario se establece un número de error.
                if ($type == 1 || $type == 2 || $type == 3) {
                    // Se obtiene la extensión del archivo.
                    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    // Se establece un nombre único para el archivo.
                    $this->imageName = uniqid() . '.' . $extension;
                    // Se guardan las dimensiones objetivo para redimensionar al guardar.
                    $this->targetWidth = $targetWidth;
                    $this->targetHeight = $targetHeight;
                    return true;
                } else {
                    $this->imageError = 'El tipo de la imagen debe ser gif, jpg o png';
                    return false;
                }
            } else {
                $this->imageError = 'El tamaño de la imagen debe ser menor a 2MB';
                return false;
            }
        } else {
            $this->imageError = 'El archivo de la imagen no existe';
            return false;
        }
    }

    /*
    *   Método para validar un correo electrónico.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un dato booleano.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateBoolean($value)
    {
        if ($value == 1 || $value == 0 || $value == true || $value == false) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar una cadena de texto (letras, digitos, espacios en blanco y signos de puntuación).
    *
    *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateString($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s\,\;\.]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar una cadena de texto sin restriccion.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateText($value)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if ($value) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un dato alfabético (letras y espacios en blanco).
    *
    *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateAlphabetic($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un dato alfanumérico (letras, dígitos y espacios en blanco).
    *
    *   Parámetros: $value (dato a validar), $minimum (longitud mínima) y $maximum (longitud máxima).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateAlphanumeric($value, $minimum, $maximum)
    {
        // Se verifica el contenido y la longitud de acuerdo con la base de datos.
        if (preg_match('/^[a-zA-Z0-9ñÑáÁéÉíÍóÓúÚ\s]{' . $minimum . ',' . $maximum . '}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un dato monetario.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateMoney($value)
    {
        // Se verifica que el número tenga una parte entera y como máximo dos cifras decimales.
        if (preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar una contraseña.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validatePassword($value)
    {
        
        if (preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/', $value)) {
            return true;
        } else {
            $this->passwordError = 'La clave debe tener al menos 8 caracteres entre especiales y alfanuméricos, al menos uno de cada uno';
        }
    }

    /*
    *   Método para validar el formato del DUI (Documento Único de Identidad).
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateDUI($value)
    {
        // Se verifica que el número tenga el formato 00000000-0.
        if (preg_match('/^[0-9]{8}[-][0-9]{1}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar un número telefónico.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validatePhone($value)
    {
        // Se verifica que el número tenga el formato 0000-0000 y que inicie con 2, 6 o 7.
        if (preg_match('/^[2,6,7]{1}[0-9]{3}[-][0-9]{4}$/', $value)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar una fecha.
    *
    *   Parámetros: $value (dato a validar).
    *   
    *   Retorno: booleano (true si el valor es correcto o false en caso contrario).
    */
    public function validateDate($value)
    {
        // Se dividen las partes de la fecha y se guardan en un arreglo en el siguiene orden: año, mes y día.
        $date = explode('-', $value);
        if (checkdate($date[1], $date[2], $date[0])) {
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Método para validar la ubicación de un archivo antes de subirlo al servidor.
    *
    *   Parámetros: $file (archivo), $path (ruta del archivo) y $name (nombre del archivo).
    *   
    *   Retorno: booleano (true si el archivo fue subido al servidor o false en caso contrario).
    */
    public function saveFile($file, $path, $name)
    {
        // Se verifica que el archivo exista.
        if ($file) {
            // Se comprueba que la ruta en el servidor exista.
            if (file_exists($path)) {
                // Se verifica que el archivo sea movido al servidor.
                if (move_uploaded_file($file['tmp_name'], $path . $name)) {
                    // Redimensionar la imagen si se especificaron dimensiones objetivo.
                    if ($this->targetWidth && $this->targetHeight) {
                        $this->resizeImage($path . $name, $this->targetWidth, $this->targetHeight);
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    *   Método para redimensionar una imagen a las dimensiones especificadas.
    *
    *   Parámetros: $filePath (ruta completa del archivo), $targetWidth (ancho objetivo) y $targetHeight (alto objetivo).
    *   
    *   Retorno: booleano (true si la imagen fue redimensionada o false en caso contrario).
    */
    private function resizeImage($filePath, $targetWidth, $targetHeight)
    {
        // Se verifica que la extensión GD esté disponible.
        if (!extension_loaded('gd')) {
            return false;
        }

        // Se obtienen las dimensiones y el tipo de la imagen.
        list($width, $height, $type) = getimagesize($filePath);

        // Si la imagen ya tiene las dimensiones objetivo, no es necesario redimensionar.
        if ($width == $targetWidth && $height == $targetHeight) {
            return true;
        }

        // Se crea la imagen de origen según el tipo.
        switch ($type) {
            case 1: // GIF
                $source = imagecreatefromgif($filePath);
                break;
            case 2: // JPG
                $source = imagecreatefromjpeg($filePath);
                break;
            case 3: // PNG
                $source = imagecreatefrompng($filePath);
                break;
            default:
                return false;
        }

        // Se crea la imagen redimensionada con las dimensiones objetivo.
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        // Se preserva la transparencia para imágenes PNG y GIF.
        if ($type == 1 || $type == 3) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
            imagefilledrectangle($resized, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        // Se redimensiona la imagen manteniendo la mejor calidad posible.
        imagecopyresampled($resized, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        // Se guarda la imagen redimensionada según su tipo.
        switch ($type) {
            case 1:
                imagegif($resized, $filePath);
                break;
            case 2:
                imagejpeg($resized, $filePath, 90);
                break;
            case 3:
                imagepng($resized, $filePath);
                break;
        }

        // Se liberan los recursos de memoria.
        imagedestroy($source);
        imagedestroy($resized);

        return true;
    }

    /*
    *   Método para validar la ubicación de un archivo antes de borrarlo del servidor.
    *
    *   Parámetros: $path (ruta del archivo) y $name (nombre del archivo).
    *   
    *   Retorno: booleano (true si el archivo fue borrado del servidor o false en caso contrario).
    */
    public function deleteFile($path, $name)
    {
        // Se verifica que la ruta exista.
        if (file_exists($path)) {
            // Se comprueba que el archivo sea borrado del servidor.
            if (@unlink($path . $name)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
