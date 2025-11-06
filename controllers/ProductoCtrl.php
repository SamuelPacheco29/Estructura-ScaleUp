<?php 

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';


class ProductoCtrl {
    private $productoModelo;
    private $categoriaModelo;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->productoModelo = new Producto;
        $this->categoriaModelo = new Categoria;

    }

    // Mostrar la lista de productos
    public function index() {
        $buscar = $_GET['buscar'] ?? '';
        $categoriaId = $_GET['categoria_id'] ?? '';
        
        if (!empty($buscar)) {
            $productos = $this->productoModelo->encontrarPorNombre($buscar);
        } elseif (!empty($categoriaId)) {
            $productos = $this->productoModelo->obtenerPorCategoria($categoriaId);
        } else {
            $productos = $this->productoModelo->obtenerTodos();
        }
        
        $categorias = $this->categoriaModelo->obtenerTodos();
        $message = $_SESSION['message'] ?? '';
        $messageType = $_SESSION['message_type'] ?? '';
        
        // Limpiar mensajes de sesion
        unset($_SESSION['message'], $_SESSION['message_type']);

        include 'views/productos/index.php';
    }


    // Crear un nuevo producto
    public function crearRegistro() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=productos');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $categoriaId = $_POST['categoria_id'] ?? '';
            $stock = (int)($_POST['stock'] ?? 0);
            $precio = (float)($_POST['precio'] ?? 0);
            
            // Validaciones
            if (empty($nombre)) {
                $_SESSION['message'] = 'El nombre del producto es requerido';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=productos&method=crear');
                exit;
            }
            
            if (empty($categoriaId)) {
                $_SESSION['message'] = 'Debe seleccionar una categoria para el producto';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=productos&method=crear');
                exit;
            }
            
            // Validar que la categoria existe
            $categoria = $this->categoriaModelo->obtenerPorId($categoriaId);
            if (!$categoria) {
                $_SESSION['message'] = 'La categoria seleccionada no existe';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=productos&method=crear');
                exit;
            }
            
            if ($stock < 0) {
                $_SESSION['message'] = 'El stock no puede ser negativo';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=productos&method=crear');
                exit;
            }
            
            if ($precio < 0) {
                $_SESSION['message'] = 'El precio no puede ser negativo';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=productos&method=crear');
                exit;
            }
            
            $data = [
                'nombre' => $nombre,
                'categoria_id' => $categoriaId,
                'stock' => $stock,
                'precio' => $precio
            ];
            
            $resultado = $this->productoModelo->crearRegistro($data);
            
            $_SESSION['message'] = $resultado['message'];
            $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: index.php?action=productos');
            exit;
        }
            $categorias = $this->categoriaModelo->obtenerTodos();
            include 'views/productos/crear.php';
    }

    // Actualizar un producto
    public function actualizarRegistro($id = null) {
        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['message'] = 'ID de producto no valido';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=productos');
            exit;
        }
        
        $producto = $this->productoModelo->obtenerPorId($id);
        
        if (!$producto) {
            $_SESSION['message'] = 'Producto no encontrado';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=productos');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $categoriaId = $_POST['categoria_id'] ?? '';
            $stock = (int)($_POST['stock'] ?? 0);
            $precio = (float)($_POST['precio'] ?? 0);
            
            // Validaciones
            if (empty($nombre)) {
                $_SESSION['message'] = 'El nombre del producto es obligatorio';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=productos&method=actualizar&id={$id}");
                exit;
            }
            
            if (empty($categoriaId)) {
                $_SESSION['message'] = 'Debe seleccionar una categoria para el producto';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=productos&method=actualizar&id={$id}");
                exit;
            }
            
            // Validar que la categoria existe
            $categoria = $this->categoriaModelo->obtenerPorId($categoriaId);
            if (!$categoria) {
                $_SESSION['message'] = 'La categoria seleccionada no existe';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=productos&method=actualizar&id={$id}");
                exit;
            }
            
            if ($stock < 0) {
                $_SESSION['message'] = 'El stock no puede ser negativo';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=productos&method=actualizar&id={$id}");
                exit;
            }
            
            if ($precio < 0) {
                $_SESSION['message'] = 'El precio no puede ser negativo';
                $_SESSION['message_type'] = 'error';
                header("Location: index.php?action=productos&method=actualizar&id={$id}");
                exit;
            }
            
            $data = [
                'nombre' => $nombre,
                'categoria_id' => !empty($categoriaId) ? $categoriaId : null,
                'stock' => $stock,
                'precio' => $precio
            ];
            
            $resultado = $this->productoModelo->actualizarRegistro($id, $data);
            
            $_SESSION['message'] = $resultado['message'];
            $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: index.php?action=productos');
            exit;
        }
        
        $categorias = $this->categoriaModelo->obtenerTodos();
        include 'views/productos/editar.php';
    }



    // Eliminar un producto
    public function eliminarRegistro($id = null) {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=productos');
            exit;
        }

        if (!$id) {
            $id = $_GET['id'] ?? null;
        }
        
        if (!$id) {
            $_SESSION['message'] = 'ID de producto no valido';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=productos');
            exit;
        }
        
        $resultado = $this->productoModelo->eliminarRegistro($id);
        
        $_SESSION['message'] = $resultado['message'];
        $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';
        
        header('Location: index.php?action=productos');
        exit;
    }

    // Mostrar estadisticas
    public function dashboard() {
        $estadisticas = $this->productoModelo->estadisticasInventario();
        $productosStockBajo = $this->productoModelo->obtenerStockBajo(10);
        $productosRecientes = $this->productoModelo->obtenerTodos();
        $categorias = $this->categoriaModelo->obtenerTodos();   
        
        include 'views/dashboard.php';
    }

    //Exportar productos a CSV
    public function exportarCSV() {
        // Configurar headers para descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="productos_' . date('Y-m-d') . '.csv"');
        
        // Obtener todos los productos
        $productos = $this->productoModelo->obtenerTodos();
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, [
            'ID',
            'Nombre',
            'Categoria',
            'Stock',
            'Precio',
            'Valor Total',
            'Fecha de Creacion'
        ]);
        
        // Escribir datos
        foreach ($productos as $productos) {
            fputcsv($output, [
                $productos['id'],
                $productos['nombre'],
                $productos['categoria_nombre'] ?? 'Sin categoria',
                $productos['stock'],
                number_format($productos['precio'], 2),
                number_format($productos['precio'] * $productos['stock'], 2),
                date('d/m/Y', strtotime($productos['created_at']))
            ]);
        }
        
        fclose($output);
        exit;
    }

}


?>