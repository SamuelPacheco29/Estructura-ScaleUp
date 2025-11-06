<?php

// MODELOS BASE

require_once __DIR__ . '/../config/database.php';

class Modelo {
    protected $db;
    protected $tabla;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Obtener la conexion de base de datos
    public function conexionBd() {
        return $this->db;
    }

    // Obtener los registros
    public function obtenerTodos() {
        $sql = "SELECT * FROM {$this->tabla} ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un registro
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Crear un registro
    public function crearRegistro($data) {
        $columnas = implode(',', array_keys($data));
        $valores = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->tabla} ({$columnas}) VALUES ({$valores})";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Actualizar un registro
    public function actualizarRegistro($id, $data) {
        $requisito = [];
        foreach (array_keys($data) as $columna) {
            $requisito[] = "{$columna} = :{$columna}";
        }
        $requisito = implode(', ', $requisito);

        $sql = "UPDATE {$this->tabla} SET {$requisito} WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }


    // Eliminar un registro
    public function eliminarRegistro($id) {
        $sql = "DELETE FROM {$this->tabla} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Buscar con condiciones 
    public function buscarRegistro($condiciones, $parametros = []) {
        $sql = "SELECT * FROM {$this->tabla} WHERE $condiciones LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);
        return $stmt->fetch();
    }
}