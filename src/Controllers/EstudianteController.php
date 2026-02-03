<?php

/**
 * Controlador de Estudiantes
 * Maneja las peticiones HTTP relacionadas con estudiantes
 */
class EstudianteController {
    private EstudianteService $service;
    private CursoService $cursoService;
    private AlergiaRepository $alergiaRepo;
    private AcudienteRepository $acudienteRepo;
    private AuthService $authService;
    private PermissionMiddleware $permissionMiddleware;
    
    public function __construct() {
        $this->service = new EstudianteService();
        $this->cursoService = new CursoService();
        $this->alergiaRepo = new AlergiaRepository();
        $this->acudienteRepo = new AcudienteRepository();
        $this->authService = new AuthService();
        $this->permissionMiddleware = new PermissionMiddleware();
    }
    
    /**
     * Listar todos los estudiantes
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('estudiantes', 'ver');
        
        // Filtrar por cursos si es maestro
        if ($this->authService->hasRole('Maestro')) {
            $cursosAsignados = $this->authService->getCursosUsuarioActual();
            $estudiantes = [];
            foreach ($cursosAsignados as $curso) {
                $estudiantesCurso = $this->service->getByCurso($curso['id_curso']);
                $estudiantes = array_merge($estudiantes, $estudiantesCurso);
            }
        } else {
            $estudiantes = $this->service->getAllWithCurso();
        }
        
        $cursos = $this->cursoService->getAll();
        
        require_once VIEWS_PATH . '/estudiantes/index.php';
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create(): void {
        $this->permissionMiddleware->requirePermission('estudiantes', 'crear');
        
        $cursos = $this->cursoService->getAll();
        require_once VIEWS_PATH . '/estudiantes/create.php';
    }
    
    /**
     * Guardar nuevo estudiante
     */
    public function store(): void {
        $this->permissionMiddleware->requirePermission('estudiantes', 'crear');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=estudiante&action=index');
            exit;
        }
        
        $file = $_FILES['documento_pdf'] ?? null;
        $result = $this->service->create($_POST, $file);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Estudiante registrado correctamente';
            header('Location: index.php?controller=estudiante&action=index');
        } else {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $_POST;
            header('Location: index.php?controller=estudiante&action=create');
        }
        exit;
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(): void {
        $this->permissionMiddleware->requirePermission('estudiantes', 'editar');
        
        $id = $_GET['id'] ?? 0;
        $estudiante = $this->service->getById($id);
        
        if (!$estudiante) {
            $_SESSION['error'] = 'Estudiante no encontrado';
            header('Location: index.php?controller=estudiante&action=index');
            exit;
        }
        
        // Verificar si maestro puede acceder a este estudiante
        if ($this->authService->hasRole('Maestro')) {
            if (!$this->authService->maestroPuedeAccederCurso($estudiante['id_curso'])) {
                $this->authService->registrarAccesoNoAutorizado(
                    'estudiantes', 
                    "Intento de editar estudiante de curso no asignado"
                );
                $this->permissionMiddleware->requirePermission('estudiantes', 'ver_todos');
            }
        }
        
        $cursos = $this->cursoService->getAll();
        $alergias = $this->alergiaRepo->findByEstudiante($id);
        $acudientes = $this->acudienteRepo->findByEstudiante($id);
        
        require_once VIEWS_PATH . '/estudiantes/edit.php';
    }
    
    /**
     * Actualizar estudiante
     */
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=estudiante&action=index');
            exit;
        }
        
        $id = $_POST['id_estudiante'] ?? 0;
        $file = $_FILES['documento_pdf'] ?? null;
        
        $result = $this->service->update($id, $_POST, $file);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Estudiante actualizado correctamente';
            header('Location: index.php?controller=estudiante&action=index');
        } else {
            $_SESSION['errors'] = $result['errors'];
            $_SESSION['old'] = $_POST;
            header("Location: index.php?controller=estudiante&action=edit&id=$id");
        }
        exit;
    }
    
    /**
     * Eliminar estudiante
     */
    public function delete(): void {
        $id = $_GET['id'] ?? 0;
        $result = $this->service->delete($id);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Estudiante eliminado correctamente';
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        header('Location: index.php?controller=estudiante&action=index');
        exit;
    }
    
    /**
     * Buscar estudiantes
     */
    public function search(): void {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            header('Location: index.php?controller=estudiante&action=index');
            exit;
        }
        
        $estudiantes = $this->service->search($query);
        $cursos = $this->cursoService->getAll();
        
        require_once VIEWS_PATH . '/estudiantes/index.php';
    }
    
    /**
     * Ver detalles de un estudiante
     */
    public function view(): void {
        $id = $_GET['id'] ?? 0;
        $estudiante = $this->service->getById($id);
        
        if (!$estudiante) {
            $_SESSION['error'] = 'Estudiante no encontrado';
            header('Location: index.php?controller=estudiante&action=index');
            exit;
        }
        
        $alergias = $this->alergiaRepo->findByEstudiante($id);
        $acudientes = $this->acudienteRepo->findByEstudiante($id);
        $curso = $this->cursoService->getById($estudiante['id_curso']);
        
        require_once VIEWS_PATH . '/estudiantes/view.php';
    }
}

