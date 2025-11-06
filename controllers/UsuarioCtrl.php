<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioCtrl {
    private $modeloUsuario;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->modeloUsuario = new Usuario();
    }

    /**
     * Listar todos los usuarios
     */
    public function index() {
        // Verificar que esté logueado y sea admin
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=dashboard');
            exit;
        }

        // Obtener solo usuarios verificados (que ya validaron el codigo de verificacion)
        $usuarios = $this->modeloUsuario->obtenerUsuariosVerificados();
        
        $message = $_SESSION['message'] ?? '';
        $messageType = $_SESSION['message_type'] ?? '';
        
        // Limpiar mensajes de sesion
        unset($_SESSION['message'], $_SESSION['message_type']);

        include 'views/usuarios/index.php';
    }

    /**
     * Cambiar rol de usuario
     */
    public function cambiarRol() {
        // Verificar que esté logueado y sea admin
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? '';
            $nuevoRol = $_POST['nuevo_rol'] ?? '';
            
            // Validaciones
            if (empty($usuarioId) || empty($nuevoRol)) {
                $_SESSION['message'] = 'Datos incompletos';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=usuarios');
                exit;
            }

            // Validar que el rol sea válido
            $rolesValidos = ['admin', 'trabajador'];
            if (!in_array($nuevoRol, $rolesValidos)) {
                $_SESSION['message'] = 'Rol inválido';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=usuarios');
                exit;
            }

            // No permitir que el usuario cambie su propio rol
            if ($usuarioId == $_SESSION['usuario_id']) {
                $_SESSION['message'] = 'No puedes cambiar tu propio rol';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=usuarios');
                exit;
            }

            // Obtener usuario actual
            $usuario = $this->modeloUsuario->obtenerPorId($usuarioId);
            if (!$usuario) {
                $_SESSION['message'] = 'Usuario no encontrado';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=usuarios');
                exit;
            }

            // Proteger el primer administrador (ID = 1)
            if ($usuarioId == 1) {
                $_SESSION['message'] = 'No se puede modificar el administrador principal del sistema';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=usuarios');
                exit;
            }

            // Cambiar el rol
            $resultado = $this->modeloUsuario->cambiarRol($usuarioId, $nuevoRol);
            
            if ($resultado) {
                $_SESSION['message'] = 'Rol actualizado exitosamente';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error al actualizar el rol';
                $_SESSION['message_type'] = 'error';
            }
        }

        header('Location: index.php?action=usuarios');
        exit;
    }

    /**
     * Exportar usuarios a CSV
     */
    public function exportarCSV() {
        // Verificar que esté logueado y sea admin
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        // Configurar headers para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="usuarios_' . date('Y-m-d') . '.csv"');
        
        // Obtener solo usuarios verificados (que son trabajadores o admin activos)
        $usuarios = $this->modeloUsuario->obtenerUsuariosVerificados();
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, [
            'ID',
            'Nombre',
            'Email',
            'Rol',
            'Estado Verificación',
            'Fecha Registro'
        ]);

        // Escribir datos
        foreach ($usuarios as $usuario) {
            fputcsv($output, [
                $usuario['id'],
                $usuario['nombre'],
                $usuario['email'],
                $usuario['rol'],
                $usuario['email_verificado'] ? 'Verificado' : 'No verificado',
                date('d/m/Y', strtotime($usuario['created_at']))
            ]);
        }
        
        fclose($output);
        exit;
    }
}

?>

