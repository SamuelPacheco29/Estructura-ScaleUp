<?php

require_once __DIR__ . '/../models/Categoria.php';

class CategoriaCtrl {
    private $categoriaModelo;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->categoriaModelo = new Categoria();
    }
    
    // Mostrar lista de categorias    
    public function index() {
        $categorias = $this->categoriaModelo->obtenerTodos();
        $message = $_SESSION['message'] ?? '';
        $messageType = $_SESSION['message_type'] ?? '';
        
        // Limpiar mensajes de sesion
        unset($_SESSION['message'], $_SESSION['message_type']);
        
        include 'views/categorias/index.php';
    }

    // Crear categoria
    public function crearRegistro() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=categorias');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            
            if (empty($nombre)) {
                $_SESSION['message'] = 'El nombre de la categoria es requerido';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=categorias');
                exit;
            }
            
            $resultadoadoado = $this->categoriaModelo->crearRegistro(['nombre' => $nombre]);
            
            $_SESSION['message'] = $resultadoadoado['message'];
            $_SESSION['message_type'] = $resultadoadoado['success'] ? 'success' : 'error';
            
            header('Location: index.php?action=categorias');
            exit;
        }
        
        // Si no es POST, mostrar formulario
        include 'views/categorias/crear.php';
    }


    // Editar categoria
    public function actualizarRegistro($id = null) {
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['message'] = 'ID de categoria no valido';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=categorias');
            exit;
        }
        
        $categorias = $this->categoriaModelo->obtenerPorId($id);
        
        if (!$categorias) {
            $_SESSION['message'] = 'Categoria no encontrada';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=categorias');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            
            if (empty($nombre)) {
                $_SESSION['message'] = 'El nombre de la categoria es obligatoria';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=categorias&method=actualizar&id={$id}");
                exit;
            }
            
            $resultadoado = $this->categoriaModelo->actualizarRegistro($id, ['nombre' => $nombre]);
            
            $_SESSION['message'] = $resultadoado['message'];
            $_SESSION['message_type'] = $resultadoado['success'] ? 'success' : 'error';
            
            header('Location: index.php?action=categorias');
            exit;
        }
        
        include 'views/categorias/editar.php';
    }

    // Eliminar categoria
    public function eliminarRegistro($id = null) {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=categorias');
            exit;
        }

        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['message'] = 'ID de categoria no valido';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=categorias');
            exit;
        }
        
        $resultado = $this->categoriaModelo->eliminarRegistro($id);
        
        $_SESSION['message'] = $resultado['message'];
        $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';
        
        header('Location: index.php?action=categorias');
        exit;
    }

    
    // Exportar los datos de categorias
    public function exportarCSV() {
        // Configurar headers para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="categorias_' . date('Y-m-d') . '.csv"');
        
        // Obtener todas las categorias
        $categorias = $this->categoriaModelo->obtenerTodos();
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, [
            'Id',
            'Nombre',
            'Fecha Creacion'
        ]);

        // Escribir datos
        foreach ($categorias as $categoria) {
            fputcsv($output, [
                $categoria['id'],
                $categoria['nombre'],
                date('d/m/Y', strtotime($categoria['created_at']))
            ]);
        }
        
        fclose($output);
        exit;
    }
}



?>