<?php

/**
 * Repositorio de Estudiantes
 * Maneja todas las operaciones de base de datos relacionadas con estudiantes
 */
class EstudianteRepository extends BaseRepository {
    protected string $table = 'estudiantes';
    protected string $primaryKey = 'id_estudiante';
    
    /**
     * Buscar estudiante por documento (registro civil o tarjeta identidad)
     */
    public function findByDocumento(string $documento): ?array {
        $sql = "SELECT * FROM estudiantes 
                WHERE registro_civil = ? OR tarjeta_identidad = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$documento, $documento]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Buscar estudiantes por nombre (búsqueda parcial)
     */
    public function findByNombre(string $nombre): array {
        $sql = "SELECT e.*, c.nombre_curso 
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                WHERE e.nombre LIKE ? OR e.apellido LIKE ?
                ORDER BY e.apellido, e.nombre";
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$nombre}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar estudiantes por curso
     */
    public function findByCurso(int $id_curso): array {
        $sql = "SELECT e.*, c.nombre_curso 
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                WHERE e.id_curso = ?
                ORDER BY e.apellido, e.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes con sus acudientes
     */
    public function findWithAcudientes(int $id_estudiante): array {
        $sql = "SELECT e.*, 
                       a.id_acudiente, a.nombre as acudiente_nombre, 
                       a.telefono, a.parentesco, ea.es_principal
                FROM estudiantes e
                LEFT JOIN estudiante_acudiente ea ON e.id_estudiante = ea.id_estudiante
                LEFT JOIN acudientes a ON ea.id_acudiente = a.id_acudiente
                WHERE e.id_estudiante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes con alergias
     */
    public function findWithAlergias(): array {
        $sql = "SELECT e.*, c.nombre_curso,
                       GROUP_CONCAT(ae.tipo_alergia SEPARATOR ', ') as alergias
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                LEFT JOIN alergias_estudiante ae ON e.id_estudiante = ae.id_estudiante
                WHERE e.tiene_alergias = 1
                GROUP BY e.id_estudiante
                ORDER BY e.apellido, e.nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Contar estudiantes en un curso
     */
    public function countByCurso(int $id_curso): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM estudiantes WHERE id_curso = ?");
        $stmt->execute([$id_curso]);
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Verificar si un documento ya existe
     */
    public function documentoExists(string $documento, ?int $exclude_id = null): bool {
        $sql = "SELECT COUNT(*) FROM estudiantes 
                WHERE (registro_civil = ? OR tarjeta_identidad = ?)";
        
        $params = [$documento, $documento];
        
        if ($exclude_id !== null) {
            $sql .= " AND id_estudiante != ?";
            $params[] = $exclude_id;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener listado completo con información de curso
     */
    public function findAllWithCurso(): array {
        $sql = "SELECT e.*, c.nombre_curso, c.jornada as curso_jornada
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                ORDER BY c.nombre_curso, e.apellido, e.nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas por jornada
     */
    public function getEstadisticasPorJornada(): array {
        $sql = "SELECT 
                    e.jornada,
                    COUNT(e.id_estudiante) as total_estudiantes,
                    COUNT(DISTINCT e.id_curso) as total_cursos,
                    SUM(e.tiene_alergias) as estudiantes_con_alergias
                FROM estudiantes e
                GROUP BY e.jornada";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

