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
    
    /**
     * Obtener estadísticas generales del sistema
     */
    public function getEstadisticasGenerales(): array {
        $sql = "SELECT 
                    COUNT(DISTINCT id_estudiante) as total_estudiantes_con_notas,
                    COUNT(*) as total_registros_notas,
                    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as total_aprobados,
                    SUM(CASE WHEN estado = 'reprobado' THEN 1 ELSE 0 END) as total_reprobados,
                    AVG(promedio) as promedio_general,
                    MAX(promedio) as mejor_promedio,
                    MIN(promedio) as peor_promedio
                FROM notas";
        $stmt = $this->db->query($sql);
        return $stmt->fetch() ?: [];
    }
    
    /**
     * Obtener rendimiento académico por curso
     */
    public function getRendimientoPorCurso(): array {
        $sql = "SELECT 
                    c.id_curso,
                    c.nombre_curso,
                    c.grado,
                    c.seccion,
                    COUNT(DISTINCT n.id_estudiante) as total_estudiantes,
                    AVG(n.promedio) as promedio_curso,
                    SUM(CASE WHEN n.estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
                    SUM(CASE WHEN n.estado = 'reprobado' THEN 1 ELSE 0 END) as reprobados
                FROM cursos c
                INNER JOIN estudiantes e ON c.id_curso = e.id_curso
                LEFT JOIN notas n ON e.id_estudiante = n.id_estudiante
                GROUP BY c.id_curso
                ORDER BY c.grado, c.seccion";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes en riesgo (promedio < 3.0)
     */
    public function getEstudiantesEnRiesgo(): array {
        $sql = "SELECT 
                    e.id_estudiante,
                    e.nombre,
                    e.apellido,
                    e.registro_civil,
                    c.nombre_curso,
                    AVG(n.promedio) as promedio_general,
                    COUNT(CASE WHEN n.estado = 'reprobado' THEN 1 END) as materias_reprobadas
                FROM estudiantes e
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                INNER JOIN notas n ON e.id_estudiante = n.id_estudiante
                GROUP BY e.id_estudiante
                HAVING promedio_general < 3.0
                ORDER BY promedio_general ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes en riesgo por curso
     */
    public function getEstudiantesEnRiesgoPorCurso(int $cursoId): array {
        $sql = "SELECT 
                    e.id_estudiante,
                    e.nombre,
                    e.apellido,
                    e.registro_civil,
                    AVG(n.promedio) as promedio_general,
                    COUNT(CASE WHEN n.estado = 'reprobado' THEN 1 END) as materias_reprobadas
                FROM estudiantes e
                INNER JOIN notas n ON e.id_estudiante = n.id_estudiante
                WHERE e.id_curso = ?
                GROUP BY e.id_estudiante
                HAVING promedio_general < 3.0
                ORDER BY promedio_general ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de un curso específico
     */
    public function getEstadisticasCurso(int $cursoId): array {
        $sql = "SELECT 
                    COUNT(DISTINCT n.id_estudiante) as total_estudiantes,
                    AVG(n.promedio) as promedio_curso,
                    SUM(CASE WHEN n.estado = 'aprobado' THEN 1 ELSE 0 END) as total_aprobados,
                    SUM(CASE WHEN n.estado = 'reprobado' THEN 1 ELSE 0 END) as total_reprobados
                FROM estudiantes e
                LEFT JOIN notas n ON e.id_estudiante = n.id_estudiante
                WHERE e.id_curso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cursoId]);
        return $stmt->fetch() ?: [];
    }
    
    /**
     * Obtener estudiantes reprobados con detalle completo (materias y notas)
     */
    public function findReprobadosDetalladoByPeriodo(int $id_periodo): array {
        $sql = "SELECT e.id_estudiante, e.nombre, e.apellido, e.registro_civil, 
                       c.nombre_curso, c.grado, c.seccion, c.jornada,
                       m.nombre_materia, n.nota_1, n.nota_2, n.nota_3, n.nota_4, n.nota_5, 
                       n.promedio, n.estado
                FROM notas n
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                INNER JOIN materias m ON n.id_materia = m.id_materia
                WHERE n.id_periodo = ? AND n.estado = 'reprobado'
                ORDER BY e.apellido, e.nombre, m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes reprobados por curso con detalle
     */
    public function findReprobadosDetalladoByPeriodoAndCurso(int $id_periodo, int $id_curso): array {
        $sql = "SELECT e.id_estudiante, e.nombre, e.apellido, e.registro_civil, 
                       c.nombre_curso, c.grado, c.seccion, c.jornada,
                       m.nombre_materia, n.nota_1, n.nota_2, n.nota_3, n.nota_4, n.nota_5, 
                       n.promedio, n.estado
                FROM notas n
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                INNER JOIN materias m ON n.id_materia = m.id_materia
                WHERE n.id_periodo = ? AND e.id_curso = ? AND n.estado = 'reprobado'
                ORDER BY e.apellido, e.nombre, m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo, $id_curso]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estudiantes reprobados por estudiante específico
     */
    public function findReprobadosDetalladoByPeriodoAndEstudiante(int $id_periodo, int $id_estudiante): array {
        $sql = "SELECT e.id_estudiante, e.nombre, e.apellido, e.registro_civil, 
                       c.nombre_curso, c.grado, c.seccion, c.jornada,
                       m.nombre_materia, n.nota_1, n.nota_2, n.nota_3, n.nota_4, n.nota_5, 
                       n.promedio, n.estado
                FROM notas n
                INNER JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
                INNER JOIN cursos c ON e.id_curso = c.id_curso
                INNER JOIN materias m ON n.id_materia = m.id_materia
                WHERE n.id_periodo = ? AND e.id_estudiante = ? AND n.estado = 'reprobado'
                ORDER BY m.nombre_materia";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_periodo, $id_estudiante]);
        return $stmt->fetchAll();
    }
}

