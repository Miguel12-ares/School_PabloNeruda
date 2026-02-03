<?php

/**
 * Controlador de Materias
 * Gestión CRUD de materias del sistema
 */
class MateriaController {
    
    private MateriaService $materiaService;
    private PermissionMiddleware $permissionMiddleware;
    private AuthService $authService;
    private AuditoriaRepository $auditoriaRepo;
    
    public function __construct() {
        $this->materiaService = new MateriaService();
        $this->permissionMiddleware = new PermissionMiddleware();
        $this->authService = new AuthService();
        $this->auditoriaRepo = new AuditoriaRepository();
    }
    
    /**
     * Listar todas las materias
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('materias', 'ver');
        
        $materias = $this->materiaService->getAll();
        
        require_once VIEWS_PATH . '/materias/index.php';
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create(): void {
        $this->permissionMiddleware->requirePermission('materias', 'crear');
        
        require_once VIEWS_PATH . '/materias/create.php';
    }
    
    /**
     * Guardar nueva materia
     */
    public function store(): void {
        $this->permissionMiddleware->requirePermission('materias', 'crear');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        $datos = [
            'nombre_materia' => $_POST['nombre_materia'] ?? '',
            'estado' => isset($_POST['estado']) ? (int)$_POST['estado'] : 1
        ];
        
        $result = $this->materiaService->create($datos);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'CREAR_MATERIA',
                'materias',
                "Materia creada: {$datos['nombre_materia']}"
            );
            
            $_SESSION['success'] = 'Materia creada correctamente';
            header('Location: /index.php?controller=materia&action=index');
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al crear la materia';
            $_SESSION['old'] = $_POST;
            header('Location: /index.php?controller=materia&action=create');
        }
        exit;
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit(): void {
        $this->permissionMiddleware->requirePermission('materias', 'editar');
        
        $id = $_GET['id'] ?? 0;
        $materia = $this->materiaService->getById($id);
        
        if (!$materia) {
            $_SESSION['error'] = 'Materia no encontrada';
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        require_once VIEWS_PATH . '/materias/edit.php';
    }
    
    /**
     * Actualizar materia
     */
    public function update(): void {
        $this->permissionMiddleware->requirePermission('materias', 'editar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        $id = $_POST['id_materia'] ?? 0;
        
        $datos = [
            'nombre_materia' => $_POST['nombre_materia'] ?? '',
            'estado' => isset($_POST['estado']) ? (int)$_POST['estado'] : 1
        ];
        
        $result = $this->materiaService->update($id, $datos);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'ACTUALIZAR_MATERIA',
                'materias',
                "Materia actualizada: ID {$id}"
            );
            
            $_SESSION['success'] = 'Materia actualizada correctamente';
            header('Location: /index.php?controller=materia&action=index');
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al actualizar la materia';
            $_SESSION['old'] = $_POST;
            header('Location: /index.php?controller=materia&action=edit&id=' . $id);
        }
        exit;
    }
    
    /**
     * Eliminar materia
     */
    public function delete(): void {
        $this->permissionMiddleware->requirePermission('materias', 'eliminar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        $id = $_POST['id_materia'] ?? 0;
        
        $result = $this->materiaService->delete($id);
        
        if ($result['success']) {
            $this->auditoriaRepo->registrarAccion(
                $this->authService->getCurrentUserId(),
                'ELIMINAR_MATERIA',
                'materias',
                "Materia eliminada: ID {$id}"
            );
            
            $_SESSION['success'] = 'Materia eliminada correctamente';
        } else {
            $_SESSION['error'] = $result['message'] ?? 'Error al eliminar la materia';
        }
        
        header('Location: /index.php?controller=materia&action=index');
        exit;
    }
    
    /**
     * Ver detalle de materia
     */
    public function view(): void {
        $this->permissionMiddleware->requirePermission('materias', 'ver');
        
        $id = $_GET['id'] ?? 0;
        $materia = $this->materiaService->getById($id);
        
        if (!$materia) {
            $_SESSION['error'] = 'Materia no encontrada';
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        // Obtener maestros que imparten esta materia
        $authRepo = new AuthRepository();
        $maestros = $authRepo->getMaestrosByMateria($id);
        
        require_once VIEWS_PATH . '/materias/view.php';
    }
    
    /**
     * Cambiar estado de materia (activa/inactiva)
     */
    public function toggleEstado(): void {
        $this->permissionMiddleware->requirePermission('materias', 'editar');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=materia&action=index');
            exit;
        }
        
        $id = $_POST['id_materia'] ?? 0;
        $materia = $this->materiaService->getById($id);
        
        if ($materia) {
            $nuevoEstado = $materia['estado'] ? 0 : 1;
            $textoEstado = $nuevoEstado ? 'activa' : 'inactiva';
            
            $result = $this->materiaService->update($id, ['estado' => $nuevoEstado]);
            
            if ($result['success']) {
                $_SESSION['success'] = "Materia marcada como {$textoEstado}";
            }
        }
        
        header('Location: /index.php?controller=materia&action=index');
        exit;
    }
}
