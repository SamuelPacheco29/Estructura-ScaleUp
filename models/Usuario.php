<?php

// Modelo de Usuario

require_once __DIR__ . '/Modelo.php';

class usuario extends Modelo {
    protected $tabla = 'usuarios';

    // Añadir nuevo usuario (Registrar)
    public function registrar($data) {
        // Verificar si el email ya existe
        $existente = $this->buscarEmail($data['email']);
        if ($existente) {
            // Si existe y NO está verificado, tratar como éxito para continuar el flujo de verificación
            if (empty($existente['email_verificado'])) {
                return [
                    'success' => true,
                    'message' => 'Usuario pendiente de verificación',
                    'usuario_id' => $existente['id'],
                    'pendiente_verificacion' => true
                ];
            }
            // Si ya está verificado, bloquear
            return ['success' => false, 'message' => 'Ya hay un usuario con este email'];
        }

        // Validar datos
        if (strlen($data['contrasena']) < 6) {
            return ['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        // Hash de la contraseña
        $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);

        // Crear usuario
        $usuarioId = $this->crearRegistro($data);

        if ($usuarioId) {
            return ['success' => true, 'message' => 'Usuario creado correctamente' , 'usuario_id' => $usuarioId];
        }

        return ['success' => false, 'message' => 'Error al registrar usuario'];
    }

    // Autentificar usuario
    public function login($email, $contrasena) {
        $usuario = $this->buscarEmail($email);

        if (!$usuario) {
            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        }

        // Verificar si el email esta verificado
        if (!$usuario['email_verificado']) {
            return ['success' => false, 'message' => 'Debes verificar tu email antes de iniciar sesión. Revisa tu bandeja de entrada'];
        }

        if (!password_verify($contrasena, $usuario['contrasena'])) {
            return ['success' => false, 'message' => 'Email o contraseña incorrectos'];
        }

        // NO devolver la contraseña
        unset($usuario['contrasena']);
        return ['success' => true, 'message' => 'Login exitoso', 'usuario' => $usuario];
    }


    // Funcion para buscar email
    public function buscarEmail($email) {
        return $this->buscarRegistro('email = ?', [$email]);
    } 


    // VERIFICACION EMAIL

    // Funcion para guardar el codigo de verificacion
    public function guardarCodigoVerificacion($usuarioId, $codigo, $fechaExpiracion) {
        $sql = "UPDATE usuarios SET codigo_verificacion = ?, codigo_expiracion = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$codigo, $fechaExpiracion, $usuarioId]);
    }
    
    // Funcion para verificar el codigo
    public function verificarCodigo($email, $codigo) {
        // Buscar usuario por email
        $sql = "SELECT * FROM usuarios WHERE email = ? AND codigo_verificacion = ? AND email_verificado = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $codigo]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return ['success' => false, 'message' => 'Codigo de verificacion incorrecto o ya utilizado'];
        }

        // Verificar si el codigo ha expirado
        if (codigoExpirado($usuario['codigo_expiracion'])) {
            return ['success' => false, 'message' => 'Codigo de verificacion expirado'];
        }

        // Si el codigo es correcto marca el email como verificado 
        $sqlUpdate = "UPDATE usuarios SET email_verificado = TRUE, codigo_verificacion = NULL, codigo_expiracion = NULL WHERE id = ?";
        $stmtUpdate = $this->db->prepare($sqlUpdate);

        if($stmtUpdate->execute([$usuario['id']])) {
            return ['success' => true, 'message' => 'Email verificado correctamente'];
        }

        return ['success' => false, 'message' => 'Error al verificar el email'];

    }

        // Reenviar codigo de verificacion 
        public function reenviarCodigo($email) {
            $usuario = $this->buscarEmail($email);
            
            if (!$usuario) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }
            
            if ($usuario['email_verificado']) {
                return ['success' => false, 'message' => 'El email ya está verificado'];
            }
            
            return ['success' => true, 'usuario_id' => $usuario['id']];
        }


        // Busca email tambien si no esta verificado
        public function obtenerPorEmail($email) {
            return $this->buscarEmail($email);
        }
           

    // Obtener solo usuarios verificados
    public function obtenerUsuariosVerificados() {
        $sql = "SELECT * FROM {$this->tabla} WHERE email_verificado = 1 ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cambiar rol de usuario
    public function cambiarRol($id, $nuevoRol) {
        $sql = "UPDATE usuarios SET rol = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nuevoRol, $id]);
    }
    
    }



?>