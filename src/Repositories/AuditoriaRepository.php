<?php

/**
 * Repositorio de Auditoría
 * Maneja el registro y consulta de logs del sistema
 */
class AuditoriaRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'auditoria';
        $this->primaryKey = 'id_auditoria';
    }
    
    /**
     * Registrar una acción en el log de auditoría
     */
    public function registrarAccion(
        int $userId,
        string $accion,
        string $modulo,
        ?string $detalles = null,
        ?string $ipAddress = null
    ): int {
        $data = [
            'id_usuario' => $userId,
            'accion' => $accion,
            'modulo' => $modulo,
            'detalles' => $detalles,
            'ip_address' => $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        return $this->create($data);
    }
    
    /**
     * Obtener logs de auditoría con filtros
     */
    public function getLogs(
        ?int $userId = null,
        ?string $modulo = null,
        ?string $accion = null,
        ?string $fechaInicio = null,
        ?string $fechaFin = null,
        int $limit = 100,
        int $offset = 0
    ): array {
        $sql = "
            SELECT a.*, u.username, u.nombre_completo
            FROM auditoria a
            INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE 1=1
        ";
        $params = [];
        
        if ($userId) {
            $sql .= " AND a.id_usuario = ?";
            $params[] = $userId;
        }
        
        if ($modulo) {
            $sql .= " AND a.modulo = ?";
            $params[] = $modulo;
        }
        
        if ($accion) {
            $sql .= " AND a.accion = ?";
            $params[] = $accion;
        }
        
        if ($fechaInicio) {
            $sql .= " AND a.fecha_accion >= ?";
            $params[] = $fechaInicio;
        }
        
        if ($fechaFin) {
            $sql .= " AND a.fecha_accion <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY a.fecha_accion DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de actividad por usuario
     */
    public function getEstadisticasPorUsuario(int $userId, int $dias = 30): array {
        $stmt = $this->db->prepare("
            SELECT 
                accion,
                COUNT(*) as cantidad,
                MAX(fecha_accion) as ultima_vez
            FROM auditoria
            WHERE id_usuario = ?
            AND fecha_accion >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY accion
            ORDER BY cantidad DESC
        ");
        $stmt->execute([$userId, $dias]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener últimas acciones de un usuario
     */
    public function getUltimasAccionesUsuario(int $userId, int $limit = 10): array {
        $stmt = $this->db->prepare("
            SELECT * FROM auditoria
            WHERE id_usuario = ?
            ORDER BY fecha_accion DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener intentos de acceso no autorizado
     */
    public function getAccesosNoAutorizados(int $dias = 7): array {
        $stmt = $this->db->prepare("
            SELECT a.*, u.username, u.nombre_completo
            FROM auditoria a
            INNER JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE a.accion IN ('ACCESO_DENEGADO', 'PERMISO_INSUFICIENTE')
            AND a.fecha_accion >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY a.fecha_accion DESC
        ");
        $stmt->execute([$dias]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener acciones distintas registradas
     */
    public function getAccionesDistintas(): array {
        $stmt = $this->db->query("
            SELECT DISTINCT accion 
            FROM auditoria 
            ORDER BY accion
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Obtener módulos distintos registrados
     */
    public function getModulosDistintos(): array {
        $stmt = $this->db->query("
            SELECT DISTINCT modulo 
            FROM auditoria 
            ORDER BY modulo
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Obtener estadísticas generales
     */
    public function getEstadisticasGenerales(): array {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_logs,
                COUNT(DISTINCT id_usuario) as usuarios_activos,
                SUM(CASE WHEN DATE(fecha_accion) = CURDATE() THEN 1 ELSE 0 END) as logs_hoy,
                SUM(CASE WHEN accion LIKE '%DENEGADO%' OR accion LIKE '%DENIED%' THEN 1 ELSE 0 END) as accesos_denegados
            FROM auditoria
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener un log por ID
     */
    public function getLogById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT a.*, u.username, u.nombre_completo
            FROM auditoria a
            LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
            WHERE a.id_auditoria = ?
        ");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Limpiar logs antiguos
     */
    public function limpiarLogsAntiguos(int $dias = 90): bool {
        $stmt = $this->db->prepare("
            DELETE FROM auditoria 
            WHERE fecha_accion < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$dias]);
    }
}

