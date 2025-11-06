<?php

require_once __DIR__ . '/../models/Movimientos.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';


class MovimientosCtrl {
    private $movimientosModelo;
    private $productoModelo;
    private $categoriaModelo;

    public function __construct() {
        // Asegurar que la sesion este iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->movimientosModelo = new Movimientos();
        $this->productoModelo = new Producto();
        $this->categoriaModelo = new Categoria();
    }

   // Crear un movimiento
    public function crearRegistro() {
        // Verificar que este logueado (
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['message'] = 'Debe iniciar sesion para registrar movimientos.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener el precio del producto 
            $productoId = $_POST['producto_id'] ?? '';
            $precioUnidad = 0;
            
            if (!empty($productoId)) {
                $producto = $this->productoModelo->obtenerPorId($productoId);
                $precioUnidad = $producto['precio'] ?? 0;
            }

            // Normalizar y validar entradas
            $tipoMovimiento = $_POST['tipo_movimiento'] ?? '';
            $cantidad = (int)($_POST['cantidad'] ?? 0);
            $referencia = trim((string)($_POST['referencia'] ?? ''));
            $notas = trim((string)($_POST['notas'] ?? ''));

            $permitidos = ['entrada','salida','ajuste'];
            if (!in_array($tipoMovimiento, $permitidos, true)) {
                $_SESSION['message'] = 'Tipo de movimiento inválido';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }

            if (empty($productoId)) {
                $_SESSION['message'] = 'Debe seleccionar un producto';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }

            if ($cantidad <= 0) {
                $_SESSION['message'] = 'La cantidad debe ser mayor a 0';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }
            if ($cantidad > 100000) {
                $_SESSION['message'] = 'La cantidad es demasiado grande (max. 100,000)';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }

            if (mb_strlen($referencia) > 100) {
                $_SESSION['message'] = 'La referencia no debe exceder 100 caracteres';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }
            if (mb_strlen($notas) > 500) {
                $_SESSION['message'] = 'Las notas no deben exceder 500 caracteres';
                $_SESSION['message_type'] = 'error';
                header('Location: index.php?action=movimientos');
                exit;
            }

            $data = [
                'producto_id' => $productoId,
                'usuario_id' => $_SESSION['usuario_id'],
                'tipo_movimiento' => $tipoMovimiento,
                'cantidad' => $cantidad,
                'precio_unidad' => $precioUnidad,
                'referencia' => $referencia,
                'notas' => $notas
            ];

            $resultado = $this->movimientosModelo->crearMovimiento($data);
            
            $_SESSION['message'] = $resultado['message'];
            $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';
            
            header('Location: index.php?action=movimientos');
            exit;
        }
        // Si no es POST, mostrar formulario
        $productos = $this->productoModelo->obtenerTodos();
        include 'views/movimientos/crear.php';
    }

    /**
     * Listar movimientos 
     */
    public function index() {
        // Verificar que esté logueado
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['message'] = 'Debe iniciar sesion para ver movimientos.';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=login');
            exit;
        }
        
        // Obtener filtros de la URL
        $filtros = [
            'fecha_movimiento' => $_GET['fecha_movimiento'] ?? '',
            'producto_id' => $_GET['producto_id'] ?? '',
            'tipo_movimiento' => $_GET['tipo_movimiento'] ?? '',
            'limite' => 30 // Limitar resultados
        ];
        
        // Si no hay fecha específica, usar últimos 30 días por defecto
        if (empty($filtros['fecha_movimiento'])) {
            $filtros['fecha_inicial'] = date('Y-m-d', strtotime('-30 days'));
            $filtros['fecha_final'] = date('Y-m-d');
        } else {
            // Si hay fecha específica, buscar solo esa fecha
            $filtros['fecha_inicial'] = $filtros['fecha_movimiento'];
            $filtros['fecha_final'] = $filtros['fecha_movimiento'];
        }
        
        $movimientos = [];
        $estadisticas = [
            'total_movimientos' => 0,
            'total_entradas' => 0,
            'total_salidas' => 0,
            'productos_movidos' => 0
        ];
        
        try {
            $movimientos = $this->movimientosModelo->obtenerConFiltros($filtros);
            
            // Calcular estadísticas básicas
            $estadisticas['total_movimientos'] = count($movimientos);
            $estadisticas['total_entradas'] = count(array_filter($movimientos, function($m) { return $m['tipo_movimiento'] === 'entrada'; }));
            $estadisticas['total_salidas'] = count(array_filter($movimientos, function($m) { return $m['tipo_movimiento'] === 'salida'; }));
            $estadisticas['productos_movidos'] = count(array_unique(array_column($movimientos, 'producto_id')));
        } catch (Exception $e) {
            // Si hay error, continuar con arrays vacíos
        }
        
        // Obtener productos para el formulario de registro
        $productos = [];
        try {
            $productos = $this->productoModelo->obtenerTodos();
        } catch (Exception $e) {
            // Si hay error, continuar con array vacío
        }
        
        include 'views/movimientos/index.php';
    }
   
    // Exportar movimientos a CSV
    public function exportarCSV() {
       

        // Obtener filtros de la URL
        $filtros = [
            'fecha_movimiento' => $_GET['fecha_movimiento'] ?? '',
            'producto_id' => $_GET['producto_id'] ?? '',
            'tipo_movimiento' => $_GET['tipo_movimiento'] ?? ''
        ];
        
        // Si no hay fecha especifica, usar ultimos 30 días por defecto
        if (empty($filtros['fecha_movimiento'])) {
            $filtros['fecha_inicio'] = date('Y-m-d', strtotime('-30 days'));
            $filtros['fecha_final'] = date('Y-m-d');
        } else {
            // Si hay fecha específica, buscar solo esa fecha
            $filtros['fecha_inicio'] = $filtros['fecha_movimiento'];
            $filtros['fecha_final'] = $filtros['fecha_movimiento'];
        }
        
        // Configurar headers para descarga
        $nombreArchivo = 'movimientos_' . date('Y-m-d');
        if (!empty($filtros['fecha_movimiento'])) {
            $nombreArchivo .= '_' . $filtros['fecha_movimiento'];
        }
        $nombreArchivo .= '.csv';
        
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        
        // Obtener movimientos con filtros
        $movimientos = $this->movimientosModelo->obtenerConFiltros($filtros);
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, [
            'ID',
            'Fecha',
            'Producto',
            'Categoria',
            'Tipo',
            'Cantidad',
            'Precio Unitario',
            'Total',
            'Usuario',
            'Referencia',
            'Notas'
        ]);

        
        // Escribir datos
        foreach ($movimientos as $movimiento) {
            fputcsv($output, [ 
                $movimiento['id'],
                date('d/m/Y H:i', strtotime($movimiento['fecha_movimiento'])),
                $movimiento['producto_nombre'],
                $movimiento['categoria_nombre'] ?? 'Sin categoria',
                ucfirst($movimiento['tipo_movimiento']),
                $movimiento['cantidad'],
                number_format($movimiento['precio_unidad'], 2),
                number_format($movimiento['precio_total'], 2),
                $movimiento['nombre_usuario'],
                $movimiento['referencia'] ?: '',
                $movimiento['notas'] ?: ''
            ]);
        }
        fclose($output);
        exit;
    }  
}



?>