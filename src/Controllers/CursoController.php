<?php

/**
 * Controlador de Cursos
 * Gestión CRUD de cursos del sistema
 */
class CursoController {
    
    private CursoService $cursoService;
    private PermissionMiddleware $permissionMiddleware;
    private AuthService $authService;
    private AuditoriaRepository $auditoriaRepo;
    
    public function __construct() {
        $this->cursoService = new CursoService();
        $this->permissionMiddleware = new PermissionMiddleware();
        $this->authService = new AuthService();
        $this->auditoriaRepo = new AuditoriaRepository();
    }
    
    /**
     * Listar todos los cursos
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'ver');
        
        $cursos = $this->cursoService->getAllWithStudentCount();
        
        require_once VIEWS_PATH . '/cursos/index.php';
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'crear');
        
        require_once VIEWS_PATH . '/cursos/create.php';
    }
    
    /**
     * Guardar nuevo curso
     */
    public function store(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'crear');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        $datos = [
            'nombre_curso' => $_POST['nombre_curso'] ?? '',
            'grado' => $_POST['grado'] ?? 0,
            'seccion' => $_POST['seccion'] ?? null,
            'jornada' => $_POST['jornada'] ?? 'mañana',
            'capacidad_maxima' => $_POST['capacidad_maxima'] ?? 30
        ];
        
        $result = $this->cursoService->create($datos);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'CREAR_CURSO',
                'cursos',
                "Curso creado: {$datos['nombre_curso']}"
            );
            
            $_SESSION['success'] = 'Curso creado correctamente';
            header('Location: /index.php?controller=curso&action=index');
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al crear el curso';
            $_SESSION['old'] = $_POST;
            header('Location: /index.php?controller=curso&action=create');
        }
        exit;
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'editar');
        
        $id = $_GET['id'] ?? 0;
        $curso = $this->cursoService->getById($id);
        
        if (!$curso) {
            $_SESSION['error'] = 'Curso no encontrado';
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        require_once VIEWS_PATH . '/cursos/edit.php';
    }
    
    /**
     * Actualizar curso
     */
    public function update(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'editar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        $id = $_POST['id_curso'] ?? 0;
        
        $datos = [
            'nombre_curso' => $_POST['nombre_curso'] ?? '',
            'grado' => $_POST['grado'] ?? 0,
            'seccion' => $_POST['seccion'] ?? null,
            'jornada' => $_POST['jornada'] ?? 'mañana',
            'capacidad_maxima' => $_POST['capacidad_maxima'] ?? 30
        ];
        
        $result = $this->cursoService->update($id, $datos);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'ACTUALIZAR_CURSO',
                'cursos',
                "Curso actualizado: ID {$id}"
            );
            
            $_SESSION['success'] = 'Curso actualizado correctamente';
            header('Location: /index.php?controller=curso&action=index');
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al actualizar el curso';
            $_SESSION['old'] = $_POST;
            header('Location: /index.php?controller=curso&action=edit&id=' . $id);
        }
        exit;
    }
    
    /**
     * Eliminar curso
     */
    public function delete(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'eliminar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        $id = $_POST['id_curso'] ?? 0;
        
        // Verificar si el curso tiene estudiantes
        $curso = $this->cursoService->getById($id);
        if ($curso && $curso['total_estudiantes'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar un curso que tiene estudiantes asignados';
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        $result = $this->cursoService->delete($id);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'ELIMINAR_CURSO',
                'cursos',
                "Curso eliminado: ID {$id}"
            );
            
            $_SESSION['success'] = 'Curso eliminado correctamente';
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al eliminar el curso';
        }
        
        header('Location: /index.php?controller=curso&action=index');
        exit;
    }
    
    /**
     * Ver detalle de curso
     */
    public function view(): void {
        $this->permissionMiddleware->requirePermission('cursos', 'ver');
        
        $id = $_GET['id'] ?? 0;
        $curso = $this->cursoService->getById($id);
        
        if (!$curso) {
            $_SESSION['error'] = 'Curso no encontrado';
            header('Location: /index.php?controller=curso&action=index');
            exit;
        }
        
        // Obtener estudiantes del curso
        $estudianteService = new EstudianteService();
        $estudiantes = $estudianteService->getByCurso($id);
        
        // Obtener maestros asignados al curso
        $authRepo = new AuthRepository();
        $maestros = $authRepo->getMaestrosByCurso($id);
        
        require_once VIEWS_PATH . '/cursos/view.php';
    }
    
    /**
     * Ver detalle completo del curso para maestros
     */
    public function detalle(): void {
        // Los maestros pueden ver detalles de sus cursos asignados
        // Los administrativos y directivos pueden ver todos
        if (!$this->authService->hasRole('Maestro')) {
            $this->permissionMiddleware->requirePermission('cursos', 'ver');
        }
        
        $id = $_GET['id'] ?? 0;
        $curso = $this->cursoService->getById($id);
        
        if (!$curso) {
            $_SESSION['error'] = 'Curso no encontrado';
            header('Location: /dashboard/maestro.php');
            exit;
        }
        
        // Si es maestro, verificar que el curso esté asignado
        if ($this->authService->hasRole('Maestro')) {
            if (!$this->authService->maestroPuedeAccederCurso($id)) {
                $_SESSION['error'] = 'No tienes permiso para acceder a este curso';
                header('Location: /dashboard/maestro.php');
                exit;
            }
        }
        
        // Obtener estudiantes del curso con sus promedios
        $estudianteService = new EstudianteService();
        $estudiantes = $estudianteService->getByCurso($id);
        
        // Obtener notas y estadísticas del curso
        $notaService = new NotaService();
        $estadisticas = $notaService->getEstadisticasCurso($id);
        
        // Obtener materias del curso
        $materiaService = new MateriaService();
        $materias = $materiaService->getMateriasByCurso($id);
        
        // Obtener maestros asignados al curso
        $authRepo = new AuthRepository();
        $maestros = $authRepo->getMaestrosByCurso($id);
        
        // Calcular promedios por estudiante
        foreach ($estudiantes as &$estudiante) {
            $promedio = $notaService->getPromedioEstudiante($estudiante['id_estudiante']);
            $estudiante['promedio_general'] = $promedio['promedio_general'] ?? 0;
            $estudiante['materias_reprobadas'] = $promedio['materias_reprobadas'] ?? 0;
        }
        
        require_once VIEWS_PATH . '/cursos/detalle.php';
    }
}
