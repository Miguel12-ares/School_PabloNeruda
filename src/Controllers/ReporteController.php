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
    private PermissionMiddleware $permissionMiddleware;
    
    public function __construct() {
        $this->estudianteService = new EstudianteService();
        $this->notaService = new NotaService();
        $this->cursoService = new CursoService();
        $this->periodoService = new PeriodoService();
        $this->permissionMiddleware = new PermissionMiddleware();
    }
    
    /**
     * Página principal de reportes
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'ver');
        require_once VIEWS_PATH . '/reportes/index.php';
    }
    
    /**
     * Reporte de estudiantes por curso
     */
    public function estudiantes_por_curso(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'ver');
        
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
    public function alergias(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'alergias');
        
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        
        $cursos = $this->cursoService->getAll();
        $todos_estudiantes = $this->estudianteService->getAll();
        
        if ($id_estudiante) {
            // Filtrar por estudiante específico
            $estudiantes = $this->estudianteService->getWithAlergias();
            $estudiantes = array_filter($estudiantes, function($est) use ($id_estudiante) {
                return $est['id_estudiante'] == $id_estudiante;
            });
        } elseif ($id_curso) {
            // Filtrar por curso
            $estudiantes = $this->estudianteService->getWithAlergiasByCurso($id_curso);
        } else {
            // Todos los estudiantes con alergias
            $estudiantes = $this->estudianteService->getWithAlergias();
        }
        
        require_once VIEWS_PATH . '/reportes/estudiantes_alergias.php';
    }
    
    /**
     * Alias para compatibilidad con URLs antiguas
     */
    public function estudiantes_alergias(): void {
        $this->alergias();
    }
    
    /**
     * Reporte de estudiantes reprobados
     */
    public function reprobados(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'reprobados');
        
        $id_periodo = $_GET['id_periodo'] ?? 0;
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        
        $periodos = $this->periodoService->getAll();
        $cursos = $this->cursoService->getAll();
        $estudiantes = [];
        $periodo_seleccionado = null;
        $curso_seleccionado = null;
        
        if ($id_periodo) {
            $periodo_seleccionado = $this->periodoService->getById($id_periodo);
            
            if ($id_estudiante) {
                // Filtrar por estudiante específico
                $estudiantes = $this->notaService->getReprobadosDetalladoPorEstudiante($id_periodo, $id_estudiante);
            } elseif ($id_curso) {
                // Filtrar por curso
                $estudiantes = $this->notaService->getReprobadosDetalladoPorCurso($id_periodo, $id_curso);
                $curso_seleccionado = $this->cursoService->getById($id_curso);
            } else {
                // Todos los reprobados
                $estudiantes = $this->notaService->getReprobadosDetallado($id_periodo);
            }
        }
        
        require_once VIEWS_PATH . '/reportes/estudiantes_reprobados.php';
    }
    
    /**
     * Alias para compatibilidad con URLs antiguas
     */
    public function estudiantes_reprobados(): void {
        $this->reprobados();
    }
    
    /**
     * Generar PDF de estudiantes reprobados
     */
    public function estudiantes_reprobados_pdf(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'reprobados');
        
        require_once __DIR__ . '/../Libraries/fpdf.php';
        require_once __DIR__ . '/../Libraries/PdfGenerator.php';
        
        $id_periodo = $_GET['id_periodo'] ?? 0;
        $id_curso = $_GET['id_curso'] ?? 0;
        
        if (!$id_periodo) {
            die('Periodo no especificado');
        }
        
        $periodo_seleccionado = $this->periodoService->getById($id_periodo);
        $curso_seleccionado = null;
        
        if ($id_curso) {
            $estudiantes = $this->notaService->getReprobadosDetalladoPorCurso($id_periodo, $id_curso);
            $curso_seleccionado = $this->cursoService->getById($id_curso);
        } else {
            $estudiantes = $this->notaService->getReprobadosDetallado($id_periodo);
        }
        
        // Crear PDF
        $pdf = new PdfGenerator('L'); // Landscape
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setHeaderText('Reporte de Estudiantes Reprobados');
        
        // Información del periodo - Compacta
        $pdf->addSectionTitle('Informacion del Reporte');
        $pdf->addInfoCard('Periodo Academico', 'Periodo ' . $periodo_seleccionado['numero_periodo'] . ' - ' . $periodo_seleccionado['anio_lectivo']);
        if ($curso_seleccionado) {
            $pdf->addInfoCard('Curso', $curso_seleccionado['nombre_curso'] . ' - ' . ucfirst($curso_seleccionado['jornada']));
        }
        $pdf->addInfoCard('Fecha de Generacion', date('d/m/Y H:i'));
        
        // Calcular estadísticas
        $estudiantes_unicos = [];
        $total_materias = count($estudiantes);
        foreach ($estudiantes as $est) {
            $estudiantes_unicos[$est['id_estudiante']] = true;
        }
        
        $pdf->addInfoCard('Estudiantes Afectados', count($estudiantes_unicos) . ' estudiantes con materias reprobadas');
        $pdf->addInfoCard('Total Materias Reprobadas', $total_materias . ' materias en estado bajo');
        $pdf->Ln(5);
        
        if (empty($estudiantes)) {
            $pdf->addAlertBox('Excelente! No hay estudiantes reprobados en este periodo', 'success');
        } else {
            // Tabla de estudiantes reprobados
            $pdf->addSectionTitle('Detalle de Materias Reprobadas');
            
            $headers = ['#', 'Estudiante', 'Curso', 'Materia', 'N1', 'N2', 'N3', 'N4', 'N5', 'Prom.'];
            $widths = [10, 55, 42, 55, 12, 12, 12, 12, 12, 15];
            
            $data = [];
            foreach ($estudiantes as $index => $est) {
                $data[] = [
                    ($index + 1),
                    $this->truncateText($est['nombre'] . ' ' . $est['apellido'], 35),
                    $this->truncateText($est['nombre_curso'], 28),
                    $this->truncateText($est['nombre_materia'], 35),
                    $est['nota_1'] ?? '-',
                    $est['nota_2'] ?? '-',
                    $est['nota_3'] ?? '-',
                    $est['nota_4'] ?? '-',
                    $est['nota_5'] ?? '-',
                    number_format($est['promedio'], 2)
                ];
            }
            
            $pdf->createTable($headers, $data, $widths);
            
            // Información adicional
            $pdf->Ln(3);
            $pdf->addSectionTitle('Observaciones y Recomendaciones');
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 4, utf8_decode(
                "- Este reporte muestra las materias reprobadas (promedio < 3.0) del periodo academico seleccionado.\n\n" .
                "- Se recomienda seguimiento personalizado y apoyo academico adicional para estos estudiantes.\n\n" .
                "- Los docentes deben implementar estrategias de refuerzo en las materias identificadas.\n\n" .
                "- Se sugiere contactar a los acudientes para informar sobre el rendimiento academico."
            ));
            
            // Escala de valoración
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(0, 5, utf8_decode('Escala de Valoracion Nacional:'), 0, 1);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(50, 4, utf8_decode('4.6 - 5.0: Superior'), 0, 0);
            $pdf->Cell(50, 4, utf8_decode('4.0 - 4.5: Alto'), 0, 0);
            $pdf->Cell(50, 4, utf8_decode('3.0 - 3.9: Basico'), 0, 0);
            $pdf->Cell(50, 4, utf8_decode('0.0 - 2.9: Bajo (Reprobado)'), 0, 1);
        }
        
        // Salida del PDF
        $filename = 'estudiantes_reprobados_P' . $periodo_seleccionado['numero_periodo'] . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
    
    /**
     * Función auxiliar para truncar texto
     */
    private function truncateText($text, $maxLength) {
        if (strlen($text) > $maxLength) {
            return substr($text, 0, $maxLength - 3) . '...';
        }
        return $text;
    }
    
    /**
     * Generar PDF de estudiantes por curso
     */
    public function estudiantes_por_curso_pdf(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'ver');
        
        require_once __DIR__ . '/../Libraries/fpdf.php';
        require_once __DIR__ . '/../Libraries/PdfGenerator.php';
        
        $id_curso = $_GET['id_curso'] ?? 0;
        
        if (!$id_curso) {
            die('Curso no especificado');
        }
        
        $curso_seleccionado = $this->cursoService->getById($id_curso);
        $estudiantes = $this->estudianteService->getByCurso($id_curso);
        
        // Crear PDF
        $pdf = new PdfGenerator();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setHeaderText('Reporte de Estudiantes por Curso');
        
        // Información del curso - Compacta
        $pdf->addSectionTitle('Informacion del Curso');
        $pdf->addInfoCard('Curso', $curso_seleccionado['nombre_curso']);
        $pdf->addInfoCard('Grado y Seccion', $curso_seleccionado['grado'] == 0 ? 'Preescolar' : $curso_seleccionado['grado'] . 'o - Seccion ' . $curso_seleccionado['seccion']);
        $pdf->addInfoCard('Jornada', ucfirst($curso_seleccionado['jornada']));
        $pdf->addInfoCard('Matriculados', count($estudiantes) . ' de ' . $curso_seleccionado['capacidad_maxima'] . ' estudiantes');
        $pdf->addInfoCard('Fecha de Generacion', date('d/m/Y H:i'));
        $pdf->Ln(5);
        
        if (empty($estudiantes)) {
            $pdf->addAlertBox('No hay estudiantes registrados en este curso', 'info');
        } else {
            // Tabla de estudiantes
            $pdf->addSectionTitle('Listado de Estudiantes');
            
            $headers = ['#', 'Nombre Completo', 'Registro Civil', 'Edad', 'Alergias', 'Acudiente Principal'];
            $widths = [8, 55, 32, 12, 16, 57];
            
            $acudienteRepo = new AcudienteRepository();
            $data = [];
            
            foreach ($estudiantes as $index => $estudiante) {
                $acudientes = $acudienteRepo->findByEstudiante($estudiante['id_estudiante']);
                $acudiente_principal = null;
                foreach ($acudientes as $ac) {
                    if ($ac['es_principal']) {
                        $acudiente_principal = $ac;
                        break;
                    }
                }
                
                $acudiente_info = $acudiente_principal 
                    ? $this->truncateText($acudiente_principal['nombre'], 20) . "\n" . $acudiente_principal['telefono']
                    : 'No registrado';
                
                $data[] = [
                    ($index + 1),
                    $this->truncateText($estudiante['nombre'] . ' ' . $estudiante['apellido'], 35),
                    $estudiante['registro_civil'],
                    $estudiante['edad'],
                    $estudiante['tiene_alergias'] ? 'Si' : 'No',
                    $acudiente_info
                ];
            }
            
            $pdf->createTable($headers, $data, $widths);
            
            // Información adicional compacta
            $con_alergias = 0;
            foreach ($estudiantes as $est) {
                if ($est['tiene_alergias']) $con_alergias++;
            }
            
            $pdf->Ln(3);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->SetTextColor(100, 100, 100);
            $porcentaje_ocupacion = ($curso_seleccionado['capacidad_maxima'] > 0) 
                ? round((count($estudiantes) / $curso_seleccionado['capacidad_maxima']) * 100, 1) 
                : 0;
            $pdf->Cell(0, 4, utf8_decode("Ocupacion: {$porcentaje_ocupacion}% | Estudiantes con alergias: {$con_alergias} | Cupos disponibles: " . ($curso_seleccionado['capacidad_maxima'] - count($estudiantes))), 0, 1, 'C');
        }
        
        // Salida del PDF
        $filename = 'estudiantes_' . str_replace(' ', '_', $curso_seleccionado['nombre_curso']) . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
    
    /**
     * Generar PDF de estudiantes con alergias
     */
    public function estudiantes_alergias_pdf(): void {
        $this->permissionMiddleware->requirePermission('reportes', 'alergias');
        
        require_once __DIR__ . '/../Libraries/fpdf.php';
        require_once __DIR__ . '/../Libraries/PdfGenerator.php';
        
        $id_curso = $_GET['id_curso'] ?? 0;
        
        if ($id_curso) {
            $estudiantes = $this->estudianteService->getWithAlergiasByCurso($id_curso);
            $curso_seleccionado = $this->cursoService->getById($id_curso);
        } else {
            $estudiantes = $this->estudianteService->getWithAlergias();
            $curso_seleccionado = null;
        }
        
        // Crear PDF
        $pdf = new PdfGenerator('L'); // Landscape
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setHeaderText('Reporte de Alergias - Emergencia Medica');
        
        // Alerta importante
        $pdf->addAlertBox('INFORMACION CRITICA PARA CASOS DE EMERGENCIA MEDICA', 'danger');
        $pdf->Ln(2);
        
        // Información del filtro
        $pdf->addSectionTitle('Informacion del Reporte');
        $pdf->addInfoCard('Alcance', $curso_seleccionado ? 'Curso: ' . $curso_seleccionado['nombre_curso'] : 'Toda la institucion');
        if ($curso_seleccionado) {
            $pdf->addInfoCard('Jornada', ucfirst($curso_seleccionado['jornada']));
        }
        $pdf->addInfoCard('Total de Casos Registrados', count($estudiantes) . ' estudiantes con alergias');
        $pdf->addInfoCard('Nivel de Prioridad', 'ALTA - Reporte de Emergencia');
        $pdf->addInfoCard('Fecha de Generacion', date('d/m/Y H:i'));
        $pdf->Ln(5);
        
        if (empty($estudiantes)) {
            $pdf->addAlertBox('No hay estudiantes con alergias registradas', 'success');
        } else {
            // Tabla de estudiantes
            $pdf->addSectionTitle('Listado de Estudiantes con Alergias');
            
            $headers = ['#', 'Nombre Completo', 'Registro', 'Curso', 'Jornada', 'Alergias', 'Acudiente - Telefono'];
            $widths = [8, 48, 28, 35, 18, 60, 40];
            
            $acudienteRepo = new AcudienteRepository();
            $data = [];
            
            foreach ($estudiantes as $index => $estudiante) {
                $acudientes = $acudienteRepo->findByEstudiante($estudiante['id_estudiante']);
                $acudiente_principal = null;
                foreach ($acudientes as $ac) {
                    if ($ac['es_principal']) {
                        $acudiente_principal = $ac;
                        break;
                    }
                }
                
                $contacto = $acudiente_principal 
                    ? $this->truncateText($acudiente_principal['nombre'], 18) . "\n" . $acudiente_principal['telefono']
                    : 'No registrado';
                
                $data[] = [
                    ($index + 1),
                    $this->truncateText($estudiante['nombre'] . ' ' . $estudiante['apellido'], 30),
                    $estudiante['registro_civil'],
                    $this->truncateText($estudiante['nombre_curso'], 22),
                    ucfirst($estudiante['jornada']),
                    $this->truncateText($estudiante['alergias'], 40),
                    $contacto
                ];
            }
            
            $pdf->createTable($headers, $data, $widths);
            
            // Protocolo de emergencia
            $pdf->Ln(3);
            $pdf->addSectionTitle('Protocolo de Emergencia Medica');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetTextColor(220, 53, 69);
            $pdf->Cell(0, 5, utf8_decode('PASOS A SEGUIR EN CASO DE EMERGENCIA ALERGICA:'), 0, 1);
            
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 4, utf8_decode(
                "1. IDENTIFICAR: Verificar inmediatamente la alergia especifica del estudiante en este reporte.\n\n" .
                "2. AISLAR: Separar al estudiante del alergeno identificado y llevarlo a un area segura.\n\n" .
                "3. CONTACTAR: Llamar de inmediato al acudiente registrado (telefono incluido en tabla).\n\n" .
                "4. EVALUAR: Observar sintomas (dificultad respiratoria, inflamacion, erupciones, mareos).\n\n" .
                "5. ACTIVAR: Si los sintomas son graves, activar protocolo de emergencia medica (112) inmediatamente.\n\n" .
                "6. DOCUMENTAR: Registrar el incidente, hora, sintomas y acciones tomadas.\n\n" .
                "7. PREVENIR: Mantener este reporte actualizado y accesible para todo el personal docente."
            ));
            
            // Nota importante
            $pdf->Ln(2);
            $pdf->SetFillColor(255, 243, 205);
            $pdf->SetDrawColor(255, 193, 7);
            $pdf->SetLineWidth(0.5);
            
            // Calcular ancho disponible
            $boxWidth = $pdf->getPageWidth() - 30;
            
            $pdf->Rect($pdf->GetX(), $pdf->GetY(), $boxWidth, 8, 'FD');
            $pdf->SetFont('Arial', 'BI', 8);
            $pdf->SetTextColor(133, 100, 4);
            $pdf->Cell($boxWidth, 8, utf8_decode('IMPORTANTE: Este reporte debe estar disponible en enfermeria, coordinacion y con cada docente.'), 0, 1, 'C');
            $pdf->SetLineWidth(0.2);
        }
        
        // Salida del PDF
        $filename = 'alergias_emergencia_' . ($curso_seleccionado ? str_replace(' ', '_', $curso_seleccionado['nombre_curso']) . '_' : '') . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
    
    /**
     * Listado de boletines por curso
     */
    public function boletines(): void {
        $id_curso = $_GET['id_curso'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        
        $cursos = $this->cursoService->getAll();
        $periodos = $this->periodoService->getAll();
        $estudiantes = [];
        $estudiante_seleccionado = null;
        $curso_seleccionado = null;
        $periodo_seleccionado = null;
        
        if ($id_estudiante && $id_periodo) {
            // Vista individual de boletín
            $estudiante_seleccionado = $this->estudianteService->getById($id_estudiante);
            $periodo_seleccionado = $this->periodoService->getById($id_periodo);
            $boletin = $this->notaService->getBoletin($id_estudiante, $id_periodo);
            $promedio_general = $this->notaService->getPromedioGeneral($id_estudiante, $id_periodo);
            require_once VIEWS_PATH . '/reportes/boletin_individual.php';
            return;
        }
        
        if ($id_curso && $id_periodo) {
            $estudiantes = $this->estudianteService->getByCurso($id_curso);
            $curso_seleccionado = $this->cursoService->getById($id_curso);
            $periodo_seleccionado = $this->periodoService->getById($id_periodo);
        }
        
        require_once VIEWS_PATH . '/reportes/boletines.php';
    }
    
    /**
     * Generar PDF de boletín individual
     */
    public function boletin_pdf(): void {
        require_once __DIR__ . '/../Libraries/fpdf.php';
        require_once __DIR__ . '/../Libraries/PdfGenerator.php';
        
        $id_estudiante = $_GET['id_estudiante'] ?? 0;
        $id_periodo = $_GET['id_periodo'] ?? 0;
        
        if (!$id_estudiante || !$id_periodo) {
            die('Parámetros incompletos');
        }
        
        $estudiante = $this->estudianteService->getById($id_estudiante);
        $periodo = $this->periodoService->getById($id_periodo);
        $boletin = $this->notaService->getBoletin($id_estudiante, $id_periodo);
        $promedio_general = $this->notaService->getPromedioGeneral($id_estudiante, $id_periodo);
        
        if (empty($boletin)) {
            die('No hay notas registradas para este estudiante en el periodo seleccionado');
        }
        
        // Crear PDF
        $pdf = new PdfGenerator('P'); // Portrait
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->setHeaderText('Boletin de Calificaciones');
        
        // Título principal con ícono de escuela
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(30, 58, 95);
        $pdf->Cell(0, 8, utf8_decode('Boletín de Calificaciones'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 5, utf8_decode('Escuela Pablo Neruda'), 0, 1, 'C');
        $pdf->Ln(5);
        
        // Información del estudiante
        $pdf->addSectionTitle('Datos del Estudiante');
        $pdf->addInfoCard('Nombre Completo', $estudiante['nombre'] . ' ' . $estudiante['apellido']);
        $pdf->addInfoCard('Registro Civil', $estudiante['registro_civil']);
        $pdf->addInfoCard('Curso', $boletin[0]['nombre_curso']);
        $pdf->addInfoCard('Periodo Academico', 'Periodo ' . $periodo['numero_periodo'] . ' - Año ' . $periodo['anio_lectivo']);
        $pdf->Ln(5);
        
        // Tabla de notas
        $pdf->addSectionTitle('Calificaciones por Materia');
        
        $headers = ['Materia', 'N1', 'N2', 'N3', 'N4', 'N5', 'Promedio', 'Estado'];
        $widths = [60, 12, 12, 12, 12, 12, 20, 20];
        
        $data = [];
        foreach ($boletin as $nota) {
            $estado = $nota['estado'] === 'aprobado' ? 'Aprobado' : 'Reprobado';
            $data[] = [
                $this->truncateText($nota['nombre_materia'], 35),
                $nota['nota_1'] ?? '-',
                $nota['nota_2'] ?? '-',
                $nota['nota_3'] ?? '-',
                $nota['nota_4'] ?? '-',
                $nota['nota_5'] ?? '-',
                number_format($nota['promedio'], 2),
                $estado
            ];
        }
        
        $pdf->createTable($headers, $data, $widths);
        
        // Promedio general con tipografía destacada
        $pdf->SetFillColor(30, 58, 95);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(136, 8, utf8_decode('PROMEDIO GENERAL:'), 1, 0, 'R', true);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(20, 8, number_format($promedio_general, 2), 1, 0, 'C', true);
        
        $estado_general = $promedio_general >= 3.0 ? 'APROBADO' : 'REPROBADO';
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(20, 8, utf8_decode($estado_general), 1, 1, 'C', true);
        
        $pdf->Ln(5);
        
        // Escala de valoración
        $pdf->addSectionTitle('Escala de Valoracion Nacional');
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Cell(88, 5, utf8_decode('4.6 - 5.0: Desempeño Superior'), 0, 0);
        $pdf->Cell(88, 5, utf8_decode('4.0 - 4.5: Desempeño Alto'), 0, 1);
        $pdf->Cell(88, 5, utf8_decode('3.0 - 3.9: Desempeño Basico'), 0, 0);
        $pdf->Cell(88, 5, utf8_decode('0.0 - 2.9: Desempeño Bajo'), 0, 1);
        
        $pdf->Ln(5);
        
        // Nota adicional
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 4, utf8_decode(
            'Este documento certifica las calificaciones del estudiante para el periodo academico indicado. ' .
            'Para cualquier consulta o aclaracion, dirigirse a la coordinacion academica de la institucion.'
        ));
        
        // Salida del PDF
        $filename = 'boletin_' . str_replace(' ', '_', $estudiante['nombre'] . '_' . $estudiante['apellido']) . '_P' . $periodo['numero_periodo'] . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
}

