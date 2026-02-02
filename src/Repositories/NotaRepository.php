<?php

/**
 * Repositorio de Notas
 * Maneja todas las operaciones de base de datos relacionadas con calificaciones
 */
class NotaRepository extends BaseRepository {
    protected string $table = 'notas';
    protected string $primaryKey = 'id_nota';
    
    /**
     * Obtener notas de un estudiante en un periodo
     */
    public function findByEstudianteAndPeriodo(int $id_estudiante, int $id_periodo): array {
        $sql = "SELECT n.*, m.nombre_materia
                FROM notas n
                INNER JOIN materias m ON n.id_materia = m.id_materia
                WHERE n.id_estudiante = ? AND n.id_periodo = ?
                ORDER BY m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante, $id_periodo]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener todas las notas de un estudiante (todos los periodos)
     */
    public function findByEstudiante(int $id_estudiante): array {
        $sql = "SELECT n.*, m.nombre_materia, p.numero_periodo, p.anio_lectivo
                FROM notas n
                INNER JOIN materias m ON n.id_materia = m.id_materia
                INNER JOIN periodos p ON n.id_periodo = p.id_periodo
                WHERE n.id_estudiante = ?
                ORDER BY p.anio_lectivo DESC, p.numero_periodo DESC, m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener notas de una materia en un periodo
     */
    public function findByMateriaAndPeriodo(int $id_materia, int $id_periodo): array {
        $sql = "SELECT n.*, e.nombre, e.apellido, e.registro_civil
                FROM notas n
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                WHERE n.id_materia = ? AND n.id_periodo = ?
                ORDER BY e.apellido, e.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_materia, $id_periodo]);
        return $stmt->fetchAll();
    }
    
    /**
     * Verificar si ya existe un registro de nota
     */
    public function existsForEstudianteMateriaAndPeriodo(int $id_estudiante, int $id_materia, int $id_periodo): bool {
        $sql = "SELECT COUNT(*) FROM notas 
                WHERE id_estudiante = ? AND id_materia = ? AND id_periodo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante, $id_materia, $id_periodo]);
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Obtener estadísticas de aprobación por materia y periodo
     */
    public function getEstadisticasByMateriaAndPeriodo(int $id_materia, int $id_periodo): array {
        $sql = "SELECT 
                    COUNT(*) as total_estudiantes,
                    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    SUM(CASE WHEN estado = 'reprobado' THEN 1 ELSE 0 END) as reprobados,
                    AVG(promedio) as promedio_general
                FROM notas
                WHERE id_materia = ? AND id_periodo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_materia, $id_periodo]);
        return $stmt->fetch() ?: [];
    }
    
    /**
     * Obtener boletín completo de un estudiante para un periodo
     */
    public function getBoletinByEstudianteAndPeriodo(int $id_estudiante, int $id_periodo): array {
        $sql = "SELECT n.*, m.nombre_materia, p.numero_periodo, p.anio_lectivo,
                       e.nombre, e.apellido, e.registro_civil, c.nombre_curso
                FROM notas n
                INNER JOIN materias m ON n.id_materia = m.id_materia
                INNER JOIN periodos p ON n.id_periodo = p.id_periodo
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                WHERE n.id_estudiante = ? AND n.id_periodo = ?
                ORDER BY m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_estudiante, $id_periodo]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes reprobados en un periodo
     */
    public function findReprobadosByPeriodo(int $id_periodo): array {
        $sql = "SELECT DISTINCT e.id_estudiante, e.nombre, e.apellido, 
                       e.registro_civil, c.nombre_curso,
                       COUNT(n.id_nota) as materias_reprobadas
                FROM notas n
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                WHERE n.id_periodo = ? AND n.estado = 'reprobado'
                GROUP BY e.id_estudiante
                ORDER BY materias_reprobadas DESC, e.apellido, e.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo]);
        return $stmt->fetchAll();
    }
}

