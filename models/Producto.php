<?php 

require_once __DIR__ . '/Modelo.php';

class Producto extends Modelo {
    protected $tabla = 'productos';

    // Obtener la informacion de los productos
    public function obtenerTodos() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM {$this->tabla} p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                ORDER BY p.nombre ASC";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return [];
    }

    // Obtener producto por el ID
    public function obtenerPorId($id)
    {
        $sql = "SELECT p.*, c.nombre as categoria_nombre FROM {$this->tabla} p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar productos por su categoria

    public function obtenerPorCategoria($categoriaId) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM {$this->tabla} p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.categoria_id = ? 
        ORDER BY p.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        return $stmt->fetchAll();
    }

    // Buscar productos por nombre
    public function encontrarPorNombre($encontrar) {
        $sql = "SELECT p.*, c.nombre as categoria_nombre 
        FROM {$this->tabla} p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.nombre LIKE ? 
        ORDER BY p.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $encontrar . '%']);
        return $stmt->fetchAll();
    }

   
    
     // Buscar producto por nombre
    public function buscarPorNombre($nombre) {
        return $this->buscarRegistro('nombre = ?', [$nombre]);
    }
    
   


    // Crear producto
    public function crearRegistro($data) {
        // Validar que el nombre no exista
        if ($this->buscarPorNombre($data['nombre'])) {
            return ['success' => false, 'message' => 'Ya existe un producto con este nombre'];
        }
        
        // Validar stock y precio
        if ($data['stock'] < 0) {
            return ['success' => false, 'message' => 'El stock no puede ser negativo'];
        }
        
        if ($data['precio'] < 0) {
            return ['success' => false, 'message' => 'El precio no puede ser negativo'];
        }
        
        $productoId = parent::crearRegistro($data);
        
        if ($productoId) {
            return ['success' => true, 'message' => 'Producto creado!', 'id' => $productoId];
        }
        
        return ['success' => false, 'message' => 'Error al crear el producto'];
    }

    // Actualizar un producto

    public function actualizarRegistro($id, $data) {
        // Verificar si existe otro producto con el mismo nombre
        $existe = $this->buscarPorNombre($data['nombre']);
        if ($existe && $existe['id'] != $id) {
            return ['success' => false, 'message' => 'Ya existe un producto con este nombre'];
        }
        
        // Validar stock y precio
        if ($data['stock'] < 0) {
            return ['success' => false, 'message' => 'El stock no puede ser negativo'];
        }
        
        if ($data['precio'] < 0) {
            return ['success' => false, 'message' => 'El precio no puede ser negativo'];
        }
        
        $result = parent::actualizarRegistro($id, $data);
        
        if ($result) {
            return ['success' => true, 'message' => 'Producto actualizado!'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar el producto'];
    }

    // Eliminar producto
    public function eliminarRegistro($id) {
        try {
            // Verificar si el producto existe
            $producto = $this->obtenerPorId($id);
            if (!$producto) {
                return ['success' => false, 'message' => 'Producto no encontrado'];
            }
            
            // Iniciar transacción
            $this->db->beginTransaction();
            
            // Contar movimientos relacionados
            $sqlCountMovimientos = "SELECT COUNT(*) as total FROM movimientos WHERE producto_id = ?";
            $stmtCount = $this->db->prepare($sqlCountMovimientos);
            $stmtCount->execute([$id]);
            $totalMovimientos = $stmtCount->fetch()['total'];
            
            // Primero eliminar todos los movimientos relacionados
            $sqlMovimientos = "DELETE FROM movimientos WHERE producto_id = ?";
            $stmtMovimientos = $this->db->prepare($sqlMovimientos);
            $stmtMovimientos->execute([$id]);
            
            // Luego eliminar el producto
            $resultado = parent::eliminarRegistro($id);
            
            if($resultado) {
                // Confirmar transacción
                $this->db->commit();
                $mensaje = "Producto '{$producto['nombre']}' eliminado correctamente";
                if ($totalMovimientos > 0) {
                    $mensaje .= " junto con {$totalMovimientos} movimiento(s) relacionado(s)";
                }
                return ['success' => true, 'message' => $mensaje];
            } else {
                // Revertir transacción si falla
                $this->db->rollBack();
                return ['success' => false, 'message' => 'Error al eliminar el producto'];
            }
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()];
        }
    }

    // Obtener las stats del inventario
    public function estadisticasInventario() {
        $sql = "SELECT COUNT(*) as total_productos,
            SUM(stock) as total_stock,
            SUM(stock * precio) as precio_total,
            AVG(precio) as precio_promedio
        FROM {$this->tabla}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener productos con stock bajo
    public function obtenerStockBajo($minimoStock = 10) {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre 
                FROM {$this->tabla} p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.stock <= ? 
                ORDER BY p.stock ASC";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt->execute([$minimoStock])) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return [];
    }
}


?>