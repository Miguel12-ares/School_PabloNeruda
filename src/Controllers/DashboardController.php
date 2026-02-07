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
        
        // No se necesitan estadísticas para el nuevo dashboard
        // El dashboard ahora solo muestra accesos rápidos en tarjetas
        
        require_once VIEWS_PATH . '/dashboard/admin.php';
    }
    
    /**
     * Dashboard Directivo
     */
    public function directivoDashboard(): void {
        $this->permissionMiddleware->requirePermission('dashboard', 'directivo');
        
        // No se necesitan estadísticas para el nuevo dashboard
        // El dashboard ahora solo muestra accesos rápidos a reportes en tarjetas
        
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

