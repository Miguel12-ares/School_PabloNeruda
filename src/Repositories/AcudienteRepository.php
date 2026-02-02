<?php

/**
 * Repositorio de Acudientes
 * Maneja todas las operaciones de base de datos relacionadas con acudientes
 */
class AcudienteRepository extends BaseRepository {
    protected string $table = 'acudientes';
    protected string $primaryKey = 'id_acudiente';
    
    /**
     * Obtener acudientes de un estudiante
     */
    public function findByEstudiante(int $id_estudiante): array {
        $sql = "SELECT a.*, ea.es_principal
                FROM acudientes a
                INNER JOIN estudiante_acudiente ea ON a.id_acudiente = ea.id_acudiente
                WHERE ea.id_estudiante = ?
                ORDER BY ea.es_principal DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    /**
     * Asociar acudiente con estudiante
     */
    public function attachToEstudiante(int $id_acudiente, int $id_estudiante, bool $es_principal = false): bool {
        $sql = "INSERT INTO estudiante_acudiente (id_estudiante, id_acudiente, es_principal) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_estudiante, $id_acudiente, $es_principal]);
    }
    
    /**
     * Desasociar acudiente de estudiante
     */
    public function detachFromEstudiante(int $id_acudiente, int $id_estudiante): bool {
        $sql = "DELETE FROM estudiante_acudiente 
                WHERE id_acudiente = ? AND id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_acudiente, $id_estudiante]);
    }
    
    /**
     * Actualizar si es acudiente principal
     */
    public function updatePrincipal(int $id_acudiente, int $id_estudiante, bool $es_principal): bool {
        $sql = "UPDATE estudiante_acudiente 
                SET es_principal = ? 
                WHERE id_acudiente = ? AND id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$es_principal, $id_acudiente, $id_estudiante]);
    }
}

