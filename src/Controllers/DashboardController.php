<?php

/**
 * Controlador de Dashboard
 * Maneja las vistas de dashboard diferenciadas por rol
 */
class DashboardController {
    
    private AuthService $authService;
    private PermissionMiddleware $permissionMiddleware;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->permissionMiddleware = new PermissionMiddleware();
    }
    
    /**
     * Dashboard Administrativo
     */
    public function adminDashboard(): void {
        $this->permissionMiddleware->requirePermission('dashboard', 'administrativo');
        
        $estudianteRepo = new EstudianteRepository();
        $cursoRepo = new CursoRepository();
        $notaRepo = new NotaRepository();
        
        // Estadísticas generales
        $totalEstudiantes = count($estudianteRepo->findAll());
        $totalCursos = count($cursoRepo->findAll());
        
        // Estudiantes por estado académico
        $estadisticas = $notaRepo->getEstadisticasGenerales();
        
        // Últimas actividades (auditoría)
        $auditoriaRepo = new AuditoriaRepository();
        $ultimasActividades = $auditoriaRepo->getLogs(null, null, null, null, null, 10);
        
        // Alertas de capacidad de cursos
        $cursosConAlerta = $cursoRepo->getCursosConAlertaCapacidad();
        
        require_once VIEWS_PATH . '/dashboard/admin.php';
    }
    
    /**
     * Dashboard Directivo
     */
    public function directivoDashboard(): void {
        $this->permissionMiddleware->requirePermission('dashboard', 'directivo');
        
        $estudianteRepo = new EstudianteRepository();
        $cursoRepo = new CursoRepository();
        $notaRepo = new NotaRepository();
        
        // Estadísticas de rendimiento académico
        $rendimientoPorCurso = $notaRepo->getRendimientoPorCurso();
        
        // Estudiantes en riesgo (promedio < 3.0)
        $estudiantesEnRiesgo = $notaRepo->getEstudiantesEnRiesgo();
        
        // Estadísticas generales
        $totalEstudiantes = count($estudianteRepo->findAll());
        $totalCursos = count($cursoRepo->findAll());
        $estadisticas = $notaRepo->getEstadisticasGenerales();
        
        // Comparativa por jornadas
        $estadisticasPorJornada = $estudianteRepo->getEstadisticasPorJornada();
        
        require_once VIEWS_PATH . '/dashboard/directivo.php';
    }
    
    /**
     * Dashboard Maestro
     */
    public function maestroDashboard(): void {
        $this->permissionMiddleware->requirePermission('dashboard', 'maestro');
        
        $userId = $this->authService->getCurrentUserId();
        $authRepo = new AuthRepository();
        $notaRepo = new NotaRepository();
        $estudianteRepo = new EstudianteRepository();
        
        // Obtener cursos asignados al maestro
        $cursosAsignados = $authRepo->getMaestroCursos($userId);
        
        // Obtener materias asignadas
        $materiasAsignadas = $authRepo->getMaestroMaterias($userId);
        
        // Estadísticas por curso del maestro
        $estadisticasPorCurso = [];
        foreach ($cursosAsignados as $curso) {
            $materias = $authRepo->getMaestroMaterias($userId, $curso['id_curso']);
            $estudiantes = $estudianteRepo->findByCurso($curso['id_curso']);
            
            $estadisticasPorCurso[] = [
                'curso' => $curso,
                'materias' => $materias,
                'total_estudiantes' => count($estudiantes),
                'estadisticas' => $notaRepo->getEstadisticasCurso($curso['id_curso'])
            ];
        }
        
        // Acceso rápido: estudiantes con alertas en cursos del maestro
        $estudiantesConAlerta = [];
        foreach ($cursosAsignados as $curso) {
            $alertas = $notaRepo->getEstudiantesEnRiesgoPorCurso($curso['id_curso']);
            $estudiantesConAlerta = array_merge($estudiantesConAlerta, $alertas);
        }
        
        require_once VIEWS_PATH . '/dashboard/maestro.php';
    }
}

