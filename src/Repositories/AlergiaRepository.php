<?php

/**
 * Repositorio de Alergias
 * Maneja todas las operaciones de base de datos relacionadas con alergias de estudiantes
 */
class AlergiaRepository {
    private PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Obtener alergias de un estudiante
     */
    public function findByEstudiante(int $id_estudiante): array {
        $sql = "SELECT * FROM alergias_estudiante WHERE id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    /**
     * Agregar alergia a un estudiante
     */
    public function create(int $id_estudiante, string $tipo_alergia): bool {
        $sql = "INSERT INTO alergias_estudiante (id_estudiante, tipo_alergia) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante, $tipo_alergia]);
    }
    
    /**
     * Eliminar una alergia específica
     */
    public function delete(int $id_alergia): bool {
        $sql = "DELETE FROM alergias_estudiante WHERE id_alergia = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_alergia]);
    }
    
    /**
     * Eliminar todas las alergias de un estudiante
     */
    public function deleteByEstudiante(int $id_estudiante): bool {
        $sql = "DELETE FROM alergias_estudiante WHERE id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante]);
    }
}

