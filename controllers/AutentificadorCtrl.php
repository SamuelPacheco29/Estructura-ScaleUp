<?php

// Controlador de Autentificacion


require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../utils/EmailService.php';
require_once __DIR__ . '/../helpers.php';

class AutentificadorCtrl {
    private $modeloUsuario;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->modeloUsuario = new usuario();
    }

    // Login
    public function login() {
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $error = '';

        // Validar campos
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if (empty($email)) {
                $error = 'El email es obligatorio';
            } elseif (empty($contrasena)) {
                $error = 'La contraseña es obligatoria';
            } else {
                $resultado = $this->modeloUsuario->login($email, $contrasena);
    
                if ($resultado['success']) {
                    $_SESSION['usuario_id'] = $resultado['usuario']['id'];
                    $_SESSION['nombre'] = $resultado['usuario']['nombre'];
                    $_SESSION['email'] = $resultado['usuario']['email'];
                    $_SESSION['usuario_rol'] = $resultado['usuario']['rol'];
    
                    // Redirigir al dashboard
                    header('Location: index.php?action=dashboard');
                    exit;
                } else {
                    $error = $resultado['message'];
                }
            }
        }
        
        // Si hay un error muestra el login con el error
        include __DIR__ . '/../views/login.php';
    }


    // Registro
    public function registrar() {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmarContrasena'] ?? '';
        $error = '';

        // Solo procesar si se envio el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar
            if (empty($nombre)) {
                $error = 'El nombre es obligatorio';
            } elseif (empty($email)) {
                $error = 'El email es obligatorio';
            } elseif (empty($contrasena)) {
                $error = 'La contraseña es obligatoria';
            } elseif ($contrasena !== $confirmarContrasena) {
                $error = 'Las contraseñas no coinciden';
            } else {
                // Intentar registrarse 
                $resultado = $this->modeloUsuario->registrar([
                    'nombre' => $nombre,
                    'email' => $email,
                    'contrasena' => $contrasena
                ]);

                if ($resultado['success']) {
                    // Se genera el codigo de verificacion
                    $codigo = generarCodigoVerificacion();

                    // Calcular tiempo de expiracion
                    $fechaExpiracion = calcularExpiracion();

                    // Guardar el codigo y fecha en la bd
                    $this->modeloUsuario->guardarCodigoVerificacion(
                        $resultado['usuario_id'],
                        $codigo,
                        $fechaExpiracion
                    );

                    // Enviar el codigo
                    $emailService = new EmailService();
                    $emailEnviado = $emailService->enviarCodigoVerificacion($email, $nombre, $codigo);

                    if ($emailEnviado) {
                        // Redirige a la pagina de verificacion
                        header('Location: index.php?action=verificar&email=' . urlencode($email));
                        exit;
                    } else {
                        $error = 'Error al enviar el codigo de verificacion';
                    }
                } else {
                    $error = $resultado['message'];
                }
            }
        }

        include __DIR__ . '/../views/registro.php';
    }

    // Verificar Email
    public function verificar() {
        $email = $_POST['email'] ?? $_GET['email'] ?? '';
        $codigo = $_POST['codigo'] ?? '';
        $error = '';
        $success = '';

        // Verificar si hay mensajes de error o éxito en la URL
        if (isset($_GET['error'])) {
            $error = urldecode($_GET['error']);
        }
        if (isset($_GET['mensaje'])) {
            $success = urldecode($_GET['mensaje']);
        }

        // SI se envio el formulario con el codigo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($codigo)) {
            if (!validarFormatoCodigo($codigo)) {
                $error = 'El codigo debe tener 6 digitos';
                // Redirigir para mantener el email en la URL
                header('Location: index.php?action=verificar&email=' . urlencode($email) . '&error=' . urlencode($error));
                exit;
            } else {
                $resultado = $this->modeloUsuario->verificarCodigo($email, $codigo);

                if ($resultado['success']) {
                    // Si se verica el email se redifige al login
                    header('Location: index.php?action=login&mensaje=Email-verificado-correctamente');
                    exit;
                } else {
                    $error = $resultado['message'];
                    // Redirigir para mantener el email en la URL
                    header('Location: index.php?action=verificar&email=' . urlencode($email) . '&error=' . urlencode($error));
                    exit;
                }
            }
        }

        include __DIR__ . '/../views/verificar.php';
    }

    // Reenviar codigo de verificacion 
    public function reenviarCodigo() {
        $email = $_POST['email'] ?? '';
        $error = '';
        $success = '';
        
        // Si no hay email, redirigir al registro
        if (empty($email)) {
            header('Location: index.php?action=registrar&error=No%20se%20pudo%20enviar%20el%20codigo.%20Por%20favor%20registrate%20nuevamente.');
            exit;
        }
        
        $usuario = $this->modeloUsuario->obtenerPorEmail($email);
        
        if (!$usuario) {
            $error = 'Usuario no encontrado';
        } elseif ($usuario['email_verificado']) {
            $error = 'El email ya esta verificado';
        } else {
            // Generar nuevo codigo
            $codigo = generarCodigoVerificacion();
            
            // Calcular nueva fecha de expiracion
            $fechaExpiracion = calcularExpiracion(15);
            
            // Actualizar codigo en la base de datos
            $this->modeloUsuario->guardarCodigoVerificacion(
                $usuario['id'], 
                $codigo, 
                $fechaExpiracion
            );
            
            // Enviar nuevo codigo por email
            $emailService = new EmailService();
            $emailEnviado = $emailService->enviarCodigoVerificacion($email, $usuario['nombre'], $codigo);
            
            if ($emailEnviado) {
                $success = 'Codigo reenviado correctamente. Revisa tu email';
            } else {
                $error = 'Error al enviar el email. Intenta de nuevo';
            }
        }
        
        // Redirigir a la pagina de verificacion con el email en la URL
        header('Location: index.php?action=verificar&email=' . urlencode($email) . ($success ? '&mensaje=' . urlencode($success) : ($error ? '&error=' . urlencode($error) : '')));
        exit;
    }


    // Loguot
    public function logout() {
        // Destruir todas las variables de la sesion
        $_SESSION = array();
        // Destruir la sesion
        session_destroy();
        //Redirigir al login
        header('Location: index.php?action=login');
        exit;
    }


}

?>