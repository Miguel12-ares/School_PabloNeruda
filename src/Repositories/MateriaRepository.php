<?php

/**
 * Repositorio de Materias
 * Maneja todas las operaciones de base de datos relacionadas con materias
 */
class MateriaRepository extends BaseRepository {
    protected string $table = 'materias';
    protected string $primaryKey = 'id_materia';
    
    /**
     * Obtener solo materias activas
     */
    public function findActive(): array {
        $stmt = $this->db->query("SELECT * FROM materias WHERE estado = 1 ORDER BY nombre_materia");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener materias de un curso específico
     */
    public function findByCurso(int $id_curso): array {
        $sql = "SELECT m.*
                FROM materias m
                INNER JOIN curso_materia cm ON m.id_materia = cm.id_materia
                WHERE cm.id_curso = ? AND m.estado = 1
                ORDER BY m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si una materia ya existe por nombre
     */
    public function existsByName(string $nombre, ?int $exclude_id = null): bool {
        $sql = "SELECT COUNT(*) FROM materias WHERE nombre_materia = ?";
        $params = [$nombre];
        
        if ($exclude_id !== null) {
            $sql .= " AND id_materia != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}

