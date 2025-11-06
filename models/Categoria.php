<?php

require_once 'models/Modelo.php';

class Categoria extends Modelo {
    protected $tabla = 'categorias';

    // Buscar las categorias ordenadas por nombre
    public function obtenerTodos() {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Buscar categoria por su nombre
    public function buscarPorNombre($nombre) {
        return $this->buscarRegistro('nombre = ?', [$nombre]);
    }

    //Verificar si una categoria tiene productos asociados
    public function comoProducto($categoriaId) {
        $sql =  "SELECT COUNT(*) as count FROM productos WHERE categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    // Crear categoria
    public function crearRegistro($data) {
        if ($this->buscarPorNombre($data['nombre'])) {
            return ['success' => false, 'message' => 'Ya existe una cateoria con este nombre'];
        }

        $categoriaId = parent::crearRegistro($data);

        if ($categoriaId) {
            return ['success' => true, 'message' => 'Categoria creada', 'id' => $categoriaId];
        }

        return ['success' => false, 'message' => 'Error al crear la categoria'];
    }

    //ACtualizar una categoria
    public function actualizarRegistro($id, $data) {
        // Busca si otra cateogira se llama igual
        $existe = $this->buscarPorNombre($data['nombre']);
        if ($existe && $existe['id'] != $id) {
            return ['success' => false, 'message' => 'Ya existe una categoria con este nombre'];
        }

        parent::actualizarRegistro($id, $data);

        return ['success' => true, 'message' => 'Categoria actualizada!'];
    }

    //Eliminar una categoria
    public function eliminarRegistro($id) {
        if ($this->comoProducto($id)) {
            return ['success' => false, 'message' => 'No se puede eliminar la categoria porque ya esta añadida en otros productos'];
        }

        $resultado = parent::eliminarRegistro($id);

        if($resultado) {
            return ['success' => true, 'message' => 'Categoria eliminada!'];
        }

        return ['success' => false, 'message' => 'Error al eliminar la categoria'];
        
    }
    
}




?>