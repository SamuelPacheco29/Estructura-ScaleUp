<?php

require_once __DIR__ . '/Modelo.php';

class Movimientos extends Modelo {
    protected $tabla = 'movimientos';

    // Obtener todos los movimientos 
    public function obtenerDetalles() {
        $sql = "SELECT * FROM vista_datos_movimientos ORDER BY fecha_movimiento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener los movimientos por productos
    public function obtenerPorProducto($productoId) {
        $sql = "SELECT * FROM vista_datos_movimientos WHERE producto_id = ? ORDER BY fecha_movimiento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productoId]);
        return $stmt->fetchAll();
    }

    // Obtener los movimientos por su tipo
    public function obtenerPorTipo($tipoMovimiento) {
        $sql = "SELECT * FROM vista_datos_movimientos WHERE tipo_movimiento = ? ORDER BY fecha_movimiento DESC";
        $stmt = $this->db->prepare();
        $stmt->execute([$tipoMovimiento]);
        return $stmt->fetchAll();
    }

    // OBtener movimientos por rango de fechas
    public function obtenerPorFecha($fechaInicial, $fechaFinal) {
        $sql = "SELECT * FROM vista_datos_movimientos WHERE DATE (fecha_movimiento) BETWEEN ? AND ? ORDER BY fecha_movimiento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fechaInicial, $fechaFinal]);
        return $stmt->fetchAll();
    }

    // Obtener movimientos con varios filtros
    public function obtenerConFiltros($filtros = []) {
        $sql = "SELECT * FROM vista_datos_movimientos WHERE 1=1";
        $parametros = [];
        
        if (!empty($filtros['fecha_inicial'])) {
            $sql .= " AND DATE(fecha_movimiento) >= ?";
            $parametros[] = $filtros['fecha_inicial'];
        }
        
        if (!empty($filtros['fecha_final'])) {
            $sql .= " AND DATE(fecha_movimiento) <= ?";
            $parametros[] = $filtros['fecha_final'];
        }
        
        if (!empty($filtros['producto_id'])) {
            $sql .= " AND producto_id = ?";
            $parametros[] = $filtros['producto_id'];
        }
        
        if (!empty($filtros['tipo_movimiento'])) {
            $sql .= " AND tipo_movimiento = ?";
            $parametros[] = $filtros['tipo_movimiento'];
        }
        
        if (!empty($filtros['usuario_id'])) {
            $sql .= " AND usuario_id = ?";
            $parametros[] = $filtros['usuario_id'];
        }
        
        $sql .= " ORDER BY fecha_movimiento DESC";
        
        // Limitar resultadoados si se especifica
        if (!empty($filtros['limite'])) {
            $sql .= " LIMIT " . (int)$filtros['limite'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

    // Registrar un nuevo movimiento

    public function crearMovimiento($data) {
        // Validar datos requeridos
        if (empty($data['producto_id']) || empty($data['usuario_id']) || empty($data['tipo_movimiento'])) {
            return ['success' => false, 'message' => 'Datos requeridos faltantes'];
        }
        
        // Validar tipo de movimiento
        $validarTipos = ['entrada', 'salida', 'ajuste'];
        if (!in_array($data['tipo_movimiento'], $validarTipos)) {
            return ['success' => false, 'message' => 'Tipo de movimiento invalido'];
        }
        
        // Validar cantidad
        if (empty($data['cantidad']) || $data['cantidad'] <= 0) {
            return ['success' => false, 'message' => 'La cantidad debe ser mayor a 0'];
        }
        
        // Calcular valor total
        $precioUnidad = (float)($data['precio_unidad'] ?? 0);
        $cantidad = (int)$data['cantidad'];
        $precioTotal = $precioUnidad * $cantidad;
        
        // Verificar stock disponible solo para salidas (no para ajustes)
        if ($data['tipo_movimiento'] === 'salida') {
            $producto = $this->db->prepare("SELECT stock FROM productos WHERE id = ?");
            $producto->execute([$data['producto_id']]);
            $stockActual = $producto->fetch()['stock'] ?? 0;

            if ($stockActual < $cantidad) {
                return ['success' => false, 'message' => 'Stock insuficiente. Stock actual: ' . $stockActual];
            }
        }
        
        // Insertar movimiento
        $insertarDatos = [
            'producto_id' => $data['producto_id'],
            'usuario_id' => $data['usuario_id'],
            'tipo_movimiento' => $data['tipo_movimiento'],
            'cantidad' => $cantidad,
            'precio_unidad' => $precioUnidad,
            'precio_total' => $precioTotal,
            'referencia' => $data['referencia'] ?? null,
            'notas' => $data['notas'] ?? null
        ];
        
        $movimientoId = parent::crearRegistro($insertarDatos);
        
        if ($movimientoId) {
            // El trigger en la base de datos actualiza el stock automáticamente
            return [
                'success' => true, 
                'message' => 'Movimiento registrado exitosamente', 
                'movimiento_id' => $movimientoId
            ];
        }
        
        return ['success' => false, 'message' => 'Error al registrar el movimiento'];
    }


    // Actualizar stock de acuerdo al tipo de movimiento
    private function actualizarStock($productoId, $tipoMovimiento, $cantidad) {
        switch ($tipoMovimiento) {
            case 'entrada':
                $sql = "UPDATE productos SET stock = stock + ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cantidad, $productoId]);
                break;
                
            case 'salida':
                // La validación de stock ya se hizo antes de crear el movimiento
                $sql = "UPDATE productos SET stock = stock - ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cantidad, $productoId]);
                break;
                
            case 'ajuste':
                // Para ajustes, establecer el stock al valor especificado
                $sql = "UPDATE productos SET stock = ? WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cantidad, $productoId]);
                break;
                
            default:
                throw new Exception("Tipo de movimiento no valido: {$tipoMovimiento}");
        }
    }
    

    // Obtener un resumen de movimiento x producto
    public function resumenProducto($productoId = null) {
        if ($productoId) {
            $sql = "SELECT * FROM vista_resumen_movimientos_producto WHERE producto_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$productoId]);
            return $stmt->fetch();
        } else {
            $sql = "SELECT * FROM vista_resumen_movimientos_producto ORDER BY total_movimientos DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }


    // Obtener las estadisticas generales de movimientos
    public function estadisticasGenerales($fechaInicial = null, $fechaFinal = null) {
        $sql = "SELECT 
                    COUNT(*) as total_movimientos,
                    SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN m.cantidad ELSE 0 END) as total_entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad ELSE 0 END) as total_salidas,
                    SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN (m.cantidad * p.precio) ELSE 0 END) as valor_entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN (m.cantidad * p.precio) ELSE 0 END) as valor_salidas,
                    COUNT(DISTINCT m.producto_id) as productos_movidos,
                    COUNT(DISTINCT m.usuario_id) as usuarios_activos
                FROM movimientos m
                LEFT JOIN productos p ON m.producto_id = p.id";
        
        $parametros = [];
        
        if ($fechaInicial && $fechaFinal) {
            $sql .= " WHERE DATE(m.fecha_movimiento) BETWEEN ? AND ?";
            $parametros = [$fechaInicial, $fechaFinal];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetch();
    }

    // Obtener top de productos
    public function obtenerTopProductos($limite = 10, $fechaInicial = null, $fechaFinal = null) {
        $sql = "SELECT 
                    p.nombre as producto_nombre,
                    c.nombre as categoria_nombre,
                    COUNT(im.id) as total_movimientos,
                    SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN m.cantidad ELSE 0 END) as entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN m.cantidad ELSE 0 END) as salidas,
                    SUM(CASE WHEN m.tipo_movimiento = 'entrada' THEN (m.cantidad * p.precio) ELSE 0 END) as valor_entradas,
                    SUM(CASE WHEN m.tipo_movimiento = 'salida' THEN (m.cantidad * p.precio) ELSE 0 END) as valor_salidas
                FROM movimientos m
                LEFT JOIN productos p ON m.producto_id = p.id
                LEFT JOIN categorias c ON p.categoria_id = c.id";
        
        $parametros = [];
        
        if ($fechaInicial && $fechaFinal) {
            $sql .= " WHERE DATE(m.fecha_movimiento) BETWEEN ? AND ?";
            $parametros[] = $fechaInicial;
            $parametros[] = $fechaFinal;
        }
        
        $sql .= " GROUP BY m.producto_id, p.nombre, c.nombre
                  ORDER BY total_movimientos DESC
                  LIMIT " . (int)$limite;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetchAll();
    }

}



?>