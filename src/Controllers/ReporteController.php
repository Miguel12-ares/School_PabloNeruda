<?php

/**
 * Controlador de Reportes
 * Maneja las peticiones HTTP relacionadas con reportes y consultas
 */
class ReporteController {
    private EstudianteService $estudianteService;
    private NotaService $notaService;
    private CursoService $cursoService;
    private PeriodoService $periodoService;
    
    public function __construct() {
        $this->estudianteService = new EstudianteService();
        $this->notaService = new NotaService();
        $this->cursoService = new CursoService();
        $this->periodoService = new PeriodoService();
    }
    
    /**
     * Página principal de reportes
     */
    public function index(): void {
        require_once VIEWS_PATH . '/reportes/index.php';
    }
    
    /**
     * Reporte de estudiantes por curso
     */
    public function estudiantes_por_curso(): void {
        $id_curso = $_GET['id_curso'] ?? 0;
        
        $cursos = $this->cursoService->getAllWithStudentCount();
        $estudiantes = [];
        $curso_seleccionado = null;
        
        if ($id_curso) {
            $estudiantes = $this->estudianteService->getByCurso($id_curso);
            $curso_seleccionado = $this->cursoService->getById($id_curso);
        }
        
        require_once VIEWS_PATH . '/reportes/estudiantes_por_curso.php';
    }
    
    /**
     * Reporte de estudiantes con alergias
     */
    public function estudiantes_alergias(): void {
        $estudiantes = $this->estudianteService->getWithAlergias();
        require_once VIEWS_PATH . '/reportes/estudiantes_alergias.php';
    }
    
    /**
     * Reporte de estudiantes reprobados
     */
    public function estudiantes_reprobados(): void {
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        $periodos = $this->periodoService->getAll();
        $estudiantes = [];
        $periodo_seleccionado = null;
        
        if ($id_periodo) {
            $estudiantes = $this->notaService->getReprobados($id_periodo);
            $periodo_seleccionado = $this->periodoService->getById($id_periodo);
        }
        
        require_once VIEWS_PATH . '/reportes/estudiantes_reprobados.php';
    }
    
    /**
     * Listado de boletines por curso
     */
    public function boletines(): void {
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        $cursos = $this->cursoService->getAll();
        $periodos = $this->periodoService->getAll();
        $estudiantes = [];
        
        if ($id_curso && $id_periodo) {
            $estudiantes = $this->estudianteService->getByCurso($id_curso);
        }
        
        require_once VIEWS_PATH . '/reportes/boletines.php';
    }
}

