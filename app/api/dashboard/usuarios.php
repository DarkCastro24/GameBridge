<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../helpers/email.php');
require_once('../../models/usuarios.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new Usuarios;
    // Se instancia la clase email.
    $email = new Correo;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idusuario'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cerrar sesion dentro del sistema 
            case 'logOut':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'Sesión eliminada correctamente';
                break;
            // Caso para cerrar sesion por inactividad del usuario
            case 'logOut2':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'La sesión ha expirado';
                break;
            // Caso para cargar los datos del usuario que inicio sesion
            case 'readProfile':
                // Ejecutamos la funcion para cargar los datos del perfil
                if ($result['dataset'] = $usuario->readProfile()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                }
                break;
            // Caso para modificar los datos del usuario que inicio sesion
            case 'editProfile':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setCorreo($_POST['correo_electronico'])) {
                    if ($usuario->setAlias($_POST['usuario'])) {
                        if ($usuario->editProfile()) {
                            $result['status'] = 1;
                            // Asignamos el nuevo alias a la variable de sesion
                            $_SESSION['usuario'] = $usuario->getAlias();
                            $result['message'] = 'Perfil modificado correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Alias incorrecto';
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
                break;
            // Caso para modificar la contraseña del usuario que inicio sesion
            case 'changePassword':
                // Verificamos si el usuario al que deseamos cambiar la clave existe en la base
                if ($usuario->setId($_SESSION['idusuario'])) {
                    // Obtenemos el form con los inputs para obtener los datos
                    $_POST = $usuario->validateForm($_POST);
                    // Verificamos si la contraseña ingresada es correcta
                    if ($usuario->checkPassword($_POST['clave_actual'])) {
                        // Comparamos el valor de la nueva contraseña con la confirmacion
                        if ($_POST['clave_nueva_1'] == $_POST['clave_nueva_2']) {
                            if ($_POST['clave_nueva_1'] != $_POST['clave_actual']) {
                                if ($_POST['clave_nueva_2'] != $_POST['clave_actual']) {
                                    if ($_SESSION['usuario'] != $_POST['clave_nueva_1']) {
                                        if ($_SESSION['usuario'] != $_POST['clave_nueva_2']) {
                                            if ($usuario->setClave($_POST['clave_nueva_1'])) {
                                                // Ejecutamos la funcion para cambiar la contraseña
                                                if ($usuario->changePassword()) {
                                                    $result['status'] = 1;
                                                    $result['message'] = 'Contraseña modificada correctamente';
                                                } else {
                                                    $result['exception'] = Database::getException();
                                                }
                                            } else {
                                                $result['exception'] = $usuario->getPasswordError();
                                            }
                                        } else {
                                            $result['exception'] = 'Clave igual al nombre de usuario';
                                        }
                                    } else {
                                        $result['exception'] = 'Clave igual al nombre de usuario';
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
                        $result['exception'] = 'No hay usuarios registrados';
                    }
                }
                break;

            case 'readDevices':
                // Ejecutamos la funcion del modelo
                if ($result['dataset'] = $usuario->readDevices()) {
                    $result['status'] = 1;
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No hay usuarios registrados';
                    }
                }
                break;

            case 'readAllParam':
                // Ejecutamos la funcion del modelo 
                $_POST = $usuario->validateForm($_POST);

                if ($result['dataset'] = $usuario->readHistory($_POST['txtIdX'])) {
                    $result['status'] = 1;
                } else {
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'Registro inexistente';
                    }
                }

                break;
            // Caso para realizar busqueda filtrada
            case 'search':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $usuario->validateForm($_POST);
                // Validamos si el contenido del search no esta vacio
                if ($_POST['search'] != '') {
                    // Ejecutamos la funcion del modelo
                    if ($result['dataset'] = $usuario->searchRows($_POST['search'])) {
                        $result['status'] = 1;
                        $rows = count($result['dataset']);
                        // Comparamos el numero de filas obtenidas
                        if ($rows > 1) {
                            $result['message'] = 'Se encontraron ' . $rows . ' coincidencias';
                        } else {
                            $result['message'] = 'Solo existe una coincidencia';
                        }
                    } else {
                        // Se ejecuta si existe algun error en la base de datos 
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'No hay coincidencias';
                        }
                    }
                } else {
                    $result['exception'] = 'Ingrese un valor para buscar';
                }
                break;
            // Caso para ingresar registros a la base de datos
            case 'create':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setAlias($_POST['txtusuario'])) {
                    if ($usuario->setCorreo($_POST['txtcorreo'])) {
                        if (isset($_POST['cmbTipo'])) {
                            if ($usuario->setTipo($_POST['cmbTipo'])) {
                                if (isset($_POST['cmbEstado'])) {
                                    if ($usuario->setEstado($_POST['cmbEstado'])) {
                                        // Comparamos que las claves sean iguales
                                        if ($_POST['txtClave'] == $_POST['txtClave2']) {
                                            if ($usuario->setClave($_POST['txtClave'])) {
                                                // Ejecutamos la funcion para agregar el usuario a la base
                                                if ($usuario->addUser()) {
                                                    $result['status'] = 1;
                                                    $result['message'] = 'Usuario registrado correctamente';
                                                } else {
                                                    // Se ejecuta si existe algun error en la base de datos 
                                                    $result['exception'] = Database::getException();;
                                                }
                                            } else {
                                                $result['exception'] = $usuario->getPasswordError();
                                            }
                                        } else {
                                            $result['exception'] = 'Claves diferentes';
                                        }
                                    } else {
                                        $result['exception'] = 'Categoría incorrecta';
                                    }
                                } else {
                                    $result['exception'] = 'Seleccione una categoría';
                                }
                            } else {
                                $result['exception'] = 'Categoría incorrecta';
                            }
                        } else {
                            $result['exception'] = 'Seleccione una categoría';
                        }
                    } else {
                        $result['exception'] = 'correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Nombre incorrecto';
                }
                break;
            // Caso para cargar los datos de un registro en especifico
            case 'readOne':
                // Obtenemos el valor del id 
                if ($usuario->setId($_POST['txtId'])) {
                    // Ejecutamos la funcion del modelo 
                    if ($result['dataset'] = $usuario->readOne()) {
                        $result['status'] = 1;
                    } else {
                        if (Database::getException()) {
                            $result['exception'] = Database::getException();
                        } else {
                            $result['exception'] = 'Usuario inexistente';
                        }
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            // Caso para modificar registros en la base de datos
            case 'update':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setId($_POST['txtId'])) {
                    if ($usuario->readOne()) {
                        if ($usuario->setTipo($_POST['cmbTipo'])) {
                            if ($usuario->setEstado($_POST['cmbEstado'])) {
                                if ($usuario->setCorreo($_POST['txtcorreo'])) {
                                    // Ejecutamos la funcion para actualizar al usuario
                                    if ($usuario->updateRow()) {
                                        $result['status'] = 1;
                                        $result['message'] = 'Usuario modificado correctamente';
                                    } else {
                                        $result['exception'] = Database::getException();
                                    }
                                } else {
                                    $result['exception'] = 'Correo incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Tipo de usuario incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Estado incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Usuario inexistente';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            case 'changePass':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setAlias($_POST['alias'])) {
                    if ($usuario->setClave($_POST['clave1'])) {

                        // Ejecutamos la funcion para actualizar al usuario
                        if ($usuario->updatePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clave actualizada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = 'Clave incorrecta';
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            // Caso para eliminar registros en la base de datos
            case 'delete':
                // Verificamos si el usuario a eliminar no es el que tiene sesion activa
                if ($_POST['txtId'] != $_SESSION['idusuario']) {
                    if ($usuario->setId($_POST['txtId'])) {
                        if ($usuario->readOne()) {
                            // Ejecutamos la funcion para eliminar datos
                            if ($usuario->deleteRow()) {
                                $result['status'] = 1;
                                $result['message'] = 'Usuario eliminado correctamente';
                            } else {
                                $result['exception'] = Database::getException();
                            }
                        } else {
                            $result['exception'] = 'Usuario inexistente';
                        }
                    } else {
                        $result['exception'] = 'Usuario incorrecto';
                    }
                } else {
                    $result['exception'] = 'No se puede eliminar a sí mismo';
                }
                break;
            // Caso para verificar si el codigo de seguridad ingresado es correcto
            case 'verifyCode':
                $_POST = $usuario->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['mail'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo('usuarios')) {
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
            // Caso para enviar el codigo de verificacion al correo del usuario
            case 'sendVerification':
                $_POST = $usuario->validateForm($_POST);
                // Generamos el codigo de seguridad 
                $code = rand(999999, 111111);
                // Concatenamos el codigo generado dentro del mensaje a enviar
                $message = "Ingrese el siguiente codigo dentro del formulario para iniciar sesión: $code";
                // Colocamos el asunto del correo a enviar
                $asunto = "Sistema de autenticación de GamebridgeStore";
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setMensaje($message)) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['correo'])) {
                        if ($email->validarCorreo('usuarios')) {
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
                                    $email->actualizarCodigo('usuarios', $code);
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

            default:
                $result['exception'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            // Caso para cargar todos datos de la tabla
            case 'readAll':
                // Ejecutamos la funcion del modelo
                if ($usuario->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existe al menos un usuario registrado';
                } else {
                    // Se ejecuta si existe algun error en la base de datos 
                    if (Database::getException()) {
                        $result['exception'] = Database::getException();
                    } else {
                        $result['exception'] = 'No existen usuarios registrados';
                    }
                }
                break;
            case 'changePass':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setAlias($_SESSION['usuario'])) {
                    if ($usuario->setClave($_POST['clave1'])) {
                        // Ejecutamos la funcion para actualizar al usuario
                        if ($usuario->updatePassword()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clave actualizada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = $usuario->getPasswordError();
                    }
                } else {
                    $result['exception'] = 'Usuario incorrecto';
                }
                break;
            // Caso para registrar un usuario en la base de datos
            case 'register':
                $_POST = $usuario->validateForm($_POST);
                if (!$usuario->readAll()) {
                    if ($usuario->setCorreo($_POST['correo'])) {
                        if ($usuario->setAlias($_POST['alias'])) {
                            if ($usuario->setTelefono($_POST['telefono'])) {
                                if ($usuario->setDui($_POST['dui'])) {
                                    if ($_POST['clave1'] == $_POST['clave2']) {

                                        if ($usuario->setClave($_POST['clave1'])) {
                                            if ($usuario->createRow()) {
                                                $result['status'] = 1;
                                                $result['message'] = 'Usuario Root creado correctamente';
                                            } else {
                                                $result['exception'] = Database::getException();
                                            }
                                        } else {
                                            $result['exception'] = $usuario->getPasswordError();
                                        }
                                    } else {
                                        $result['exception'] = 'Las claves no coinciden';
                                    }
                                } else {
                                    $result['exception'] = 'DUI incorrecto';
                                }
                            } else {
                                $result['exception'] = 'Teléfono incorrecto';
                            }
                        } else {
                            $result['exception'] = 'Usuario incorrecto';
                        }
                    } else {
                        $result['exception'] = 'Correo incorrecto';
                    }
                } else {
                    $result['exception'] = 'Ya existe un usuario registrado';
                }
                break;

            case 'sendEmail':
                $_POST = $usuario->validateForm($_POST);
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
                        if ($email->validarCorreo('usuarios')) {
                            // Validamos si el correo ingresado tiene formato correcto
                            if ($email->setAsunto($asunto)) {
                                // Ejecutamos la funcion para enviar el correo electronico
                                if ($email->enviarCorreo()) {
                                    $result['status'] = 1;
                                    // Colocamos el mensaje de exito 
                                    $result['message'] = 'Código enviado correctamente';
                                    // Guardamos el correo al que se envio el código
                                    $_SESSION['mail'] = $email->getCorreo();
                                    // Ejecutamos funcion para obtener el usuario del correo ingresado
                                    $usuario->obtenerUsuario($_SESSION['mail']);
                                    // Ejecutamos funcion para actualizar el codigo de recuperacion del usuario en la base de datos
                                    $email->actualizarCodigo('usuarios', $code);
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
            // Caso para verificar si el codigo de seguridad ingresado es correcto
            case 'verifyCode':
                $_POST = $usuario->validateForm($_POST);
                // Validmos el formato del mensaje que se enviara en el correo
                if ($email->setCodigo($_POST['codigo'])) {
                    // Validamos si el correo ingresado tiene formato correcto
                    if ($email->setCorreo($_SESSION['mail'])) {
                        // Ejecutamos la funcion para validar el codigo de seguridad
                        if ($email->validarCodigo('usuarios')) {
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
            case 'changePassword':
                // Obtenemos el form con los inputs para obtener los datos
                $_POST = $usuario->validateForm($_POST);
                if ($usuario->setCorreo($_SESSION['email'])) {
                    if ($usuario->setClave($_POST['clave1'])) {
                        // Ejecutamos la funcion para actualizar al usuario
                        if ($usuario->updatePass()) {
                            $result['status'] = 1;
                            $result['message'] = 'Clave actualizada correctamente';
                        } else {
                            $result['exception'] = Database::getException();
                        }
                    } else {
                        $result['exception'] = $usuario->getPasswordError();
                    }
                } else {
                    $result['exception'] = 'Correo incorrecto';
                }
                break;
            // Caso para iniciar sesion en el sistema 
            case 'logIn':
                // Obtenemos el post para tener acceso a los inputs del formulario
                $_POST = $usuario->validateForm($_POST);
                // Ejecutamos la funcion para verificar si existe el usuario
                if ($usuario->checkUser($_POST['alias'])) {
                    if ($usuario->checkState($_POST['alias'])) {
                        // Ejecutamos la funcion para verificar si la clave es correcta
                        if ($usuario->checkPassword($_POST['clave'])) {
                            if ($usuario->checkDate($usuario->getFecha())) {
                                $result['status'] = 1;
                                // Ejecutamos la funcion para registro de inicio de sesion
                                $usuario->historialUsuario();
                                // Ejecutamos la funcion para actualizar los intentos del usuario a 0
                                $usuario->intentosUsuario(0);
                                // Asignamos los valores a las variables de sesion
                                $_SESSION['idusuario'] = $usuario->getId();
                                $_SESSION['tipo'] = $usuario->getTipo();
                                $_SESSION['correo'] = $usuario->getCorreo();
                                // Mostramos mensaje de exito
                                $result['message'] = 'Contraseña correcta pero debes autenticar tu usuario';
                            } else {
                                if (Database::getException()) {
                                    $result['exception'] = Database::getException();
                                } else {
                                    // Mensaje de usuario inactivo
                                    $result['exception'] = 'Debes actualizar tu contraseña por seguridad';
                                }
                            }
                        } else {
                            // Creamos una variable de sesion para guardar los intentos del usuario
                            $usuario->setIntentos($usuario->getIntentos() + 1);
                            $_SESSION['intentos'] = $usuario->getIntentos();
                            $usuario->intentosUsuario($_SESSION['intentos']);
                            if ($_SESSION['intentos'] > 2) {
                                // Ejecutamos la funcion que verifica si la clave es correcta
                                if ($usuario->desactivateUser($_POST['alias'])) {
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
            // Caso para cerrar sesion por inactividad del usuario
            case 'logOut2':
                // Ejecutamos el metodo para cerrar sesion
                unset($_SESSION['idusuario']);
                $result['status'] = 1;
                // Mostramos mensaje de confirmacion
                $result['message'] = 'La sesión ha expirado';
                break;
            // Si el caso no coincide con ninguno de los casos anteriores se muestra el siguiente mensaje
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
