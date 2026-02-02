<?php

/**
 * Controlador de Notas
 * Maneja las peticiones HTTP relacionadas con calificaciones
 */
class NotaController {
    private NotaService $service;
    private EstudianteService $estudianteService;
    private MateriaService $materiaService;
    private PeriodoService $periodoService;
    private CursoService $cursoService;
    
    public function __construct() {
        $this->service = new NotaService();
        $this->estudianteService = new EstudianteService();
        $this->materiaService = new MateriaService();
        $this->periodoService = new PeriodoService();
        $this->cursoService = new CursoService();
    }
    
    /**
     * Página principal de notas
     */
    public function index(): void {
        $cursos = $this->cursoService->getAll();
        $periodos = $this->periodoService->getAll();
        
        require_once VIEWS_PATH . '/notas/index.php';
    }
    
    /**
     * Formulario para registrar notas
     */
    public function registrar(): void {
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        if (!$id_curso || !$id_periodo) {
            $_SESSION['error'] = 'Debe seleccionar un curso y un periodo';
            header('Location: index.php?controller=nota&action=index');
            exit;
        }
        
        $estudiantes = $this->estudianteService->getByCurso($id_curso);
        $materias = $this->materiaService->getActive();
        $periodo = $this->periodoService->getById($id_periodo);
        $curso = $this->cursoService->getById($id_curso);
        
        require_once VIEWS_PATH . '/notas/registrar.php';
    }
    
    /**
     * Guardar notas
     */
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=nota&action=index');
            exit;
        }
        
        $result = $this->service->saveNotas($_POST);
        
        if ($result['success']) {
            echo json_encode($result);
        } else {
            echo json_encode($result);
        }
        exit;
    }
    
    /**
     * Ver boletín de un estudiante
     */
    public function boletin(): void {
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        if (!$id_estudiante || !$id_periodo) {
            $_SESSION['error'] = 'Parámetros inválidos';
            header('Location: index.php?controller=nota&action=index');
            exit;
        }
        
        $boletin = $this->service->getBoletin($id_estudiante, $id_periodo);
        $estudiante = $this->estudianteService->getById($id_estudiante);
        $periodo = $this->periodoService->getById($id_periodo);
        $promedio_general = $this->service->getPromedioGeneral($id_estudiante, $id_periodo);
        
        require_once VIEWS_PATH . '/notas/boletin.php';
    }
    
    /**
     * Obtener notas de un estudiante (AJAX)
     */
    public function getNotas(): void {
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        $id_materia = $_GET['id_materia'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        $notas = $this->service->getNotasByEstudianteAndPeriodo($id_estudiante, $id_periodo);
        
        $notaMateria = null;
        foreach ($notas as $nota) {
            if ($nota['id_materia'] == $id_materia) {
                $notaMateria = $nota;
                break;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($notaMateria);
        exit;
    }
}

