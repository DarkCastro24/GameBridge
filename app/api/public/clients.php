<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/clientes.php');
require_once('../../helpers/email.php');


// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new Clientes;
    $email = new Correo;

    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'recaptcha' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como cliente para realizar las acciones correspondientes.
    if (isset($_SESSION['idcliente'])) {
        // Se compara la acción a realizar cuando un cliente ha iniciado sesión.
        switch ($_GET['action']) {
            case 'logOut': //metodo para cerrar sesion

                unset($_SESSION['idcliente']);
                unset($_SESSION['correo_electronico']);
                unset($_SESSION['mail']);


                $result['status'] = 1;
                $result['message'] = 'Sesión eliminada correctamente';

                break;

            case 'logOut2': //metodo para cerrar sesion

                unset($_SESSION['idcliente']);
                unset($_SESSION['correo_electronico']);
                unset($_SESSION['mail']);


                $result['status'] = 1;
                $result['message'] = 'La sesión ha expirado';

                break;
            case 'readProfile': //metodo para leer el perfil del cliente que ha iniciado sesion
                if ($result['dataset'] = $cliente->readProfile()) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Cliente inexistente';
                    }
                }
                break;
            case 'editProfile': //metodo para editar los datos del cliente
                $_POST = $cliente->validateForm($_POST);
                if ($cliente->setCorreo($_POST['correo_electronico'])) {
                    if ($cliente->setNombres($_POST['nombres'])) {
                        if ($cliente->setApellidos($_POST['apellidos'])) {
                            if ($cliente->setDui($_POST['dui'])) {
                                if ($cliente->editProfile()) {
                                    $result['status'] = 1;
                                    $_SESSION['correo_electronico'] = $cliente->getCorreo();
                                    $result['message'] = 'Perfil modificado correctamente';
                                } else {
                                    $result['exception'] = Database::getException();
                                }
                            } else {
                                $result['exception'] = 'DUI incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Apellido incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Nombre incorrecto';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
                break;
            case 'changePassword':
                // Verificamos si el usuario al que deseamos cambiar la clave existe en la base
                if ($cliente->setId($_SESSION['idcliente'])) {
                    // Obtenemos el form con los inputs para obtener los datos
                    $_POST = $cliente->validateForm($_POST);
                    // Verificamos si la contraseña ingresada es correcta
                    if ($cliente->checkPassword($_POST['clave_actual'])) {
                        // Comparamos el valor de la nueva contraseña con la confirmacion
                        if ($_POST['clave_nueva_1'] == $_POST['clave_nueva_2']) {
                            if ($_POST['clave_nueva_1'] != $_POST['clave_actual']) {
                                if ($_POST['clave_nueva_2'] != $_POST['clave_actual']) {
                                    if ($_SESSION['correo_electronico'] != $_POST['clave_nueva_1']) {
                                        if ($_SESSION['correo_electronico'] != $_POST['clave_nueva_2']) {
                                            if ($cliente->setClave($_POST['clave_nueva_1'])) {
                                                // Ejecutamos la funcion para cambiar la contraseña
                                                if ($cliente->changePassword()) {
                                                    $result['status'] = 1;
                                                    $result['message'] = 'Contraseña modificada correctamente';
                                                } else {
                                                    $result['exception'] = Database::getException();
                                                }
                                            } else {
                                                $result['exception'] = $cliente->getPasswordError();
                                            }
                                        } else {
                                            $result['exception'] = 'Clave igual al correo';
                                        }
                                    } else {
                                        $result['exception'] = 'Clave igual al correo';
                                    }
                                } else {
                                    $result['exception'] = 'Clave igual a la actual';
                                }
                            } else {
                                $result['exception'] = 'Clave igual a la actual';
                            }
                        } else {
                            $result['exception'] = 'Claves nuevas diferentes';
                        }
                    } else {
                        $result['exception'] = 'Clave actual incorrecta';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;



            case 'readDevices':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $cliente->readDevices()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay dispositivos registrados';
                    }
                }
                break;

            case 'readAllParam':
                // Ejecutamos la funcion del modelo 
                $_POST = $cliente->validateForm($_POST);

                if ($result['dataset'] = $cliente->readHistory($_POST['txtIdX'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Registro inexistente';
                    }
                }

                break;
                // Caso para cargar todos datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $usuario->readAll()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay clientes registrados';
                    }
                }
                break;


            case 'sendVerification':
                $_POST = $cliente->validateForm($_POST);
                // Generamos el codigo de seguridad 
                $code = rand(999999, 111111);
                // Concatenamos el codigo generado dentro del mensaje a enviar
                $message = "Ingrese el siguiente codigo dentro del formulario para iniciar sesión: $code";
                // Colocamos el asunto del correo a enviar
                $asunto = "Sistema de autenticación de GamebridgeStore";
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setMensaje($message)) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['correo_electronico'])) {
                        if ($email->validarCorreo('clientes')) {
                            // Validamos si el correo ingresado tiene formato correcto
                            if ($email->setAsunto($asunto)) {
                                // Ejecutamos la funcion para enviar el correo electronico
                                if ($email->enviarCorreo()) {
                                    $result['status'] = 1;
                                    // Colocamos el mensaje de exito 
                                    $result['message'] = 'Código enviado correctamente';
                                    // Guardamos el correo al que se envio el código
                                    $_SESSION['mail'] = $email->getCorreo();
                                    // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                    $email->actualizarCodigo('clientes', $code);
                                } else {
                                    // En caso que el correo no se envie mostramos el error
                                    $result['exception'] = $_SESSION['error'];
                                }
                            } else {
                                $result['exception'] = 'Asunto incorrecto';
                            }
                        } else {
                            $result['exception'] = 'El correo ingresado no esta registrado';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Mensaje incorrecto';
                }
                break;

            case 'verifyCode':
                $_POST = $cliente->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['correo_electronico'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo2('clientes')) {
                            $result['status'] = 1;
                            // Colocamos el mensaje de exito 
                            $result['message'] = 'El código ingresado es correcto, bienvenido ' . $_SESSION['nombres'] . '';
                        } else {
                            // En caso que el correo no se envie mostramos el error
                            $result['exception'] = 'El código ingresado no es correcto';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Mensaje incorrecto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el cliente no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'register': //metodo para que el cliente se registre en el sitio y pueda comprar
                $_POST = $cliente->validateForm($_POST);
                // Se sanea el valor del token para evitar datos maliciosos.
                $token = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_SANITIZE_STRING);
                if ($token) {
                    $secretKey = '6Le5NEYcAAAAAPKPtmOlWHtNiyj-YwPE3KWOxurP';
                    $ip = $_SERVER['REMOTE_ADDR'];

                    $data = array(
                        'secret' => $secretKey,
                        'response' => $token,
                        'remoteip' => $ip
                    );

                    $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data)
                        ),
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false
                        )
                    );

                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $context  = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                    $captcha = json_decode($response, true);

                    if ($captcha['success']) {
                        if ($cliente->setNombres($_POST['nombre'])) {
                            if ($cliente->setApellidos($_POST['apellido'])) {
                                if ($cliente->setCorreo($_POST['email'])) {
                                    if ($cliente->setDUI($_POST['DUI'])) {
                                        if ($_POST['clave1'] == $_POST['clave2']) {
                                            if ($_POST['clave1'] != $_POST['email']) {
                                                if ($cliente->setClave($_POST['clave1'])) {
                                                    if ($cliente->registerClient()) {
                                                        $result['status'] = 1;
                                                        $result['message'] = 'Cliente registrado correctamente';
                                                    } else {
                                                        $result['exception'] = Database::getException();
                                                    }
                                                } else {
                                                    $result['exception'] = $cliente->getPasswordError();
                                                }
                                            } else {
                                                $result['exception'] = 'Claves igual al correo electrónico';
                                            }
                                        } else {
                                            $result['exception'] = 'Claves diferentes';
                                        }
                                    } else {
                                        $result['exception'] = 'DUI incorrecto';
                                    }
                                } else {
                                    $result['exception'] = 'Correo incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Apellidos incorrectos';
                            }
                        } else {
                            $result['exception'] = 'Nombres incorrectos';
                        }
                    } else {
                        $result['recaptcha'] = 1;
                        $result['exception'] = 'No eres un humano';
                    }
                } else {
                    $result['exception'] = 'Ocurrió un problema al cargar el reCAPTCHA';
                }
                break;


            case 'logIn':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $cliente->validateForm($_POST);
                // Ejecutamos la funcion para verificar si existe el usuario
                if ($cliente->checkUser($_POST['email'])) {
                    if ($cliente->checkState($_POST['email'])) {
                        // Ejecutamos la funcion para verificar si la clave es correcta
                        if ($cliente->checkPassword($_POST['clave'])) {
                            if ($cliente->checkDate($cliente->getFecha())) {
                                $result['status'] = 1;
                                // Ejecutamos la funcion para registro de inicio de sesion
                                $cliente->historialCliente();
                                // Ejecutamos la funcion para actualizar los intentos del usuario a 0
                                $cliente->intentosCliente(0);
                                // Asignamos los valores a las variables de sesion

                                // Mostramos mensaje de exito
                                $result['message'] = 'Contraseña correcta pero debes autenticar tu cuenta';

                                $_SESSION['idcliente'] = $cliente->getId();
                                $_SESSION['correo_electronico'] = $cliente->getCorreo();
                                $_SESSION['nombres'] = $cliente->getNombre();
                            } else {
                                $result['exception'] = 'Debes actualizar tu contraseña por seguridad';

                                $_SESSION['mail'] = $_POST['email'];
                                $_SESSION['clave'] = $_POST['clave'];
                            }
                        } else {
                            // Creamos una variable de sesion para guardar los intentos del usuario
                            $cliente->setIntentos($cliente->getIntentos() + 1);
                            $_SESSION['intentos'] = $cliente->getIntentos();
                            $cliente->intentosCliente($_SESSION['intentos']);
                            if ($_SESSION['intentos'] > 2) {
                                // Ejecutamos la funcion que verifica si la clave es correcta
                                if ($cliente->desactivateUser($_POST['email'])) {
                                    // Mostramos mensaje de alerta
                                    $result['exception'] = 'Limite de 3 intentos de inicio de sesíon alcanzado';
                                } else {
                                    // En caso exista un error de validacion se mostrara su respectivo mensaje
                                    $_SESSION['intentos'] = 0;
                                    if (Database::getException()) {
                                        $result['exception'] = Database::getException();
                                    } else {
                                        // Mensaje de usuario inactivo
                                        $result['exception'] = 'Error al desactivar usuario';
                                    }
                                }
                            } else {
                                if (Database::getException()) {
                                    $result['exception'] = Database::getException();
                                } else {
                                    // Mensaje de clave incorrecta
                                    $result['exception'] = 'La clave ingresada es incorrecta';
                                }
                            }
                        }
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            // Mensaje de usuario inactivo
                            $result['exception'] = 'El usuario se encuentra inactivo';
                        }
                        $_SESSION['intentos'] = 0;
                    }
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Alias incorrecto';
                    }
                }
                break;

            case 'logInGoogle':
                // Método para login solo por correo (usado por Google OAuth)
                // Recibe correo electrónico y realiza login directo
                $_POST = $cliente->validateForm($_POST);
                
                if (!isset($_POST['email']) || empty($_POST['email'])) {
                    $result['exception'] = 'Correo electrónico requerido';
                    break;
                }
                
                // Verificar si el usuario existe
                if ($cliente->checkUser($_POST['email'])) {
                    // Verificar que esté activo
                    if ($cliente->checkState($_POST['email'])) {
                        // Usuario existe y está activo → iniciar sesión
                        $result['status'] = 1;
                        $cliente->historialCliente();
                        $cliente->intentosCliente(0);
                        
                        $_SESSION['idcliente'] = $cliente->getId();
                        $_SESSION['correo_electronico'] = $cliente->getCorreo();
                        $_SESSION['nombres'] = $cliente->getNombre();
                        $_SESSION['google'] = true;
                        
                        $result['message'] = 'Sesión iniciada correctamente';
                    } else {
                        $result['exception'] = 'Tu cuenta se encuentra bloqueada';
                    }
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'El usuario no existe';
                    }
                }
                break;

            case 'checkEmail':
                // Método para verificar si un email está registrado
                $_POST = $cliente->validateForm($_POST);
                
                if (!isset($_POST['email']) || empty($_POST['email'])) {
                    $result['exception'] = 'Correo electrónico requerido';
                    break;
                }
                
                if ($cliente->checkUser($_POST['email'])) {
                    $result['status'] = 1;
                    $result['message'] = 'El correo está registrado';
                    $result['exists'] = true;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exists'] = false;
                        $result['message'] = 'El correo no está registrado';
                    }
                }
                break;

            case 'sendEmail':
                $_POST = $cliente->validateForm($_POST);
                // Generamos el codigo de seguridad 
                $code = rand(999999, 111111);
                // Concatenamos el codigo generado dentro del mensaje a enviar
                $message = "Has solicitado recuperar tu contraseña por medio de correo electrónico, su código de seguridad es: $code";
                // Colocamos el asunto del correo a enviar
                $asunto = "Recuperación de contraseña GamebridgeStore";
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setMensaje($message)) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_POST['correo'])) {
                        if ($email->validarCorreo('clientes')) {
                            // Validamos si el correo ingresado tiene formato correcto
                            if ($email->setAsunto($asunto)) {
                                // Ejecutamos la funcion para enviar el correo electronico
                                if ($email->enviarCorreo()) {
                                    $result['status'] = 1;
                                    // Colocamos el mensaje de exito 
                                    $result['message'] = 'Código enviado correctamente';
                                    // Guardamos el correo al que se envio el código
                                    $_SESSION['mail'] = $email->getCorreo();
                                    // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                    $email->actualizarCodigo('clientes', $code);
                                } else {
                                    // En caso que el correo no se envie mostramos el error
                                    $result['exception'] = $_SESSION['error'];
                                }
                            } else {
                                $result['exception'] = 'Asunto incorrecto';
                            }
                        } else {
                            $result['exception'] = 'El correo ingresado no esta registrado';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Mensaje incorrecto';
                }
                break;

            case 'verifyCode':
                $_POST = $cliente->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['mail'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo('clientes')) {
                            $result['status'] = 1;
                            // Colocamos el mensaje de exito 
                            $result['message'] = 'El código ingresado es correcto';
                        } else {
                            // En caso que el correo no se envie mostramos el error
                            $result['exception'] = 'El código ingresado no es correcto';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Mensaje incorrecto';
                }
                break;

            case 'changePass':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $cliente->validateForm($_POST);
                if ($cliente->setCorreo($_SESSION['mail'])) {
                    if ($cliente->setClave($_POST['clave1'])) {
                        // Ejecutamos la funcion para actualizar al usuario
                        if ($cliente->updatePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clave actualizada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = $cliente->getPasswordError();
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            default:
                $result['exception'] = 'Acción no disponible fuera de la sesión';
        }
    }
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
