<?php

/**
 * Repositorio de Cursos
 * Maneja todas las operaciones de base de datos relacionadas con cursos
 */
class CursoRepository extends BaseRepository {
    protected string $table = 'cursos';
    protected string $primaryKey = 'id_curso';
    
    /**
     * Buscar curso por nombre
     */
    public function findByNombre(string $nombre): ?array {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE nombre_curso = ? LIMIT 1");
        $stmt->execute([$nombre]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Obtener cursos por jornada
     */
    public function findByJornada(string $jornada): array {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE jornada = ? ORDER BY grado, seccion");
        $stmt->execute([$jornada]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener cursos por grado
     */
    public function findByGrado(int $grado): array {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE grado = ? ORDER BY seccion");
        $stmt->execute([$grado]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener curso con materias asignadas
     */
    public function findWithMaterias(int $id_curso): array {
        $sql = "SELECT c.*, m.id_materia, m.nombre_materia
                FROM cursos c
                LEFT JOIN curso_materia cm ON c.id_curso = cm.id_curso
                LEFT JOIN materias m ON cm.id_materia = m.id_materia
                WHERE c.id_curso = ? AND (m.estado = 1 OR m.estado IS NULL)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si un curso tiene capacidad disponible
     */
    public function hasCapacity(int $id_curso): bool {
        $sql = "SELECT c.capacidad_maxima, COUNT(e.id_estudiante) as total_estudiantes
                FROM cursos c
                LEFT JOIN estudiantes e ON c.id_curso = e.id_curso
                WHERE c.id_curso = ?
                GROUP BY c.id_curso";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_curso]);
        $result = $stmt->fetch();
        
        if (!$result) return false;
        
        return $result['total_estudiantes'] < $result['capacidad_maxima'];
    }
    
    /**
     * Obtener todos los cursos con conteo de estudiantes
     */
    public function findAllWithStudentCount(): array {
        $sql = "SELECT c.*, COUNT(e.id_estudiante) as total_estudiantes
                FROM cursos c
                LEFT JOIN estudiantes e ON c.id_curso = e.id_curso
                GROUP BY c.id_curso
                ORDER BY c.grado, c.seccion";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener cursos con alerta de capacidad (80% o más ocupado)
     */
    public function getCursosConAlertaCapacidad(): array {
        $sql = "SELECT c.*, 
                       COUNT(e.id_estudiante) as total_estudiantes,
                       c.capacidad_maxima,
                       ROUND((COUNT(e.id_estudiante) / c.capacidad_maxima) * 100, 2) as porcentaje_ocupacion
                FROM cursos c
                LEFT JOIN estudiantes e ON c.id_curso = e.id_curso
                GROUP BY c.id_curso
                HAVING porcentaje_ocupacion >= 80
                ORDER BY porcentaje_ocupacion DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}

