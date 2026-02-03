<?php

/**
 * Controlador de Auditoría
 * Gestión de logs y auditoría del sistema
 */
class AuditoriaController {
    
    private AuditoriaRepository $auditoriaRepo;
    private PermissionMiddleware $permissionMiddleware;
    private AuthRepository $authRepo;
    
    public function __construct() {
        $this->auditoriaRepo = new AuditoriaRepository();
        $this->permissionMiddleware = new PermissionMiddleware();
        $this->authRepo = new AuthRepository();
    }
    
    /**
     * Página principal de auditoría con filtros
     */
    public function index(): void {
        $this->permissionMiddleware->requirePermission('auditoria', 'ver');
        
        // Obtener parámetros de filtro
        $filtros = [
            'id_usuario' => $_GET['id_usuario'] ?? null,
            'accion' => $_GET['accion'] ?? null,
            'modulo' => $_GET['modulo'] ?? null,
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
            'limite' => $_GET['limite'] ?? 50
        ];
        
        // Obtener logs con filtros
        $logs = $this->auditoriaRepo->getLogs(
            $filtros['id_usuario'],
            $filtros['modulo'],
            $filtros['accion'],
            $filtros['fecha_desde'],
            $filtros['fecha_hasta'],
            $filtros['limite']
        );
        
        // Obtener datos para los filtros
        $usuarios = $this->authRepo->findAllWithRoles();
        $acciones = $this->auditoriaRepo->getAccionesDistintas();
        $modulos = $this->auditoriaRepo->getModulosDistintos();
        
        // Estadísticas generales
        $estadisticas = $this->auditoriaRepo->getEstadisticasGenerales();
        
        require_once VIEWS_PATH . '/auditoria/index.php';
    }
    
    /**
     * Ver detalle de un log específico
     */
    public function view(): void {
        $this->permissionMiddleware->requirePermission('auditoria', 'ver');
        
        $id = $_GET['id'] ?? 0;
        $log = $this->auditoriaRepo->getLogById($id);
        
        if (!$log) {
            $_SESSION['error'] = 'Log no encontrado';
            header('Location: /index.php?controller=auditoria&action=index');
            exit;
        }
        
        require_once VIEWS_PATH . '/auditoria/view.php';
    }
    
    /**
     * Exportar logs a CSV
     */
    public function exportar(): void {
        $this->permissionMiddleware->requirePermission('auditoria', 'ver');
        
        // Obtener parámetros de filtro
        $filtros = [
            'id_usuario' => $_GET['id_usuario'] ?? null,
            'accion' => $_GET['accion'] ?? null,
            'modulo' => $_GET['modulo'] ?? null,
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
            'limite' => 1000 // Límite mayor para exportación
        ];
        
        $logs = $this->auditoriaRepo->getLogs(
            $filtros['id_usuario'],
            $filtros['modulo'],
            $filtros['accion'],
            $filtros['fecha_desde'],
            $filtros['fecha_hasta'],
            $filtros['limite']
        );
        
        // Configurar headers para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="auditoria_' . date('Y-m-d_His') . '.csv"');
        
        // Crear archivo CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, ['ID', 'Fecha/Hora', 'Usuario', 'Acción', 'Módulo', 'Detalles', 'IP']);
        
        // Datos
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id_auditoria'],
                $log['fecha_accion'],
                $log['username'] ?? 'Sistema',
                $log['accion'],
                $log['modulo'],
                $log['detalles'],
                $log['ip_address']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Limpiar logs antiguos
     */
    public function limpiar(): void {
        $this->permissionMiddleware->requirePermission('auditoria', 'ver');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=auditoria&action=index');
            exit;
        }
        
        $dias = $_POST['dias'] ?? 90;
        
        $result = $this->auditoriaRepo->limpiarLogsAntiguos($dias);
        
        if ($result) {
            $_SESSION['success'] = "Logs anteriores a {$dias} días eliminados correctamente";
        } else {
            $_SESSION['error'] = 'Error al limpiar logs';
        }
        
        header('Location: /index.php?controller=auditoria&action=index');
        exit;
    }
}
