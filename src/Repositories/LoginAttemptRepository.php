<?php

/**
 * Repositorio de Intentos de Login
 * Controla y previene ataques de fuerza bruta
 */
class LoginAttemptRepository extends BaseRepository {
    
    private const MAX_INTENTOS = 5;
    private const TIEMPO_BLOQUEO_MINUTOS = 15;
    
    public function __construct() {
        parent::__construct();
        $this->table = 'intentos_login';
        $this->primaryKey = 'id';
    }
    
    /**
     * Registrar intento de login
     */
    public function registrarIntento(string $username, bool $exitoso, ?string $ipAddress = null): void {
        $data = [
            'username' => $username,
            'ip_address' => $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'exitoso' => $exitoso ? 1 : 0
        ];
        
        $this->create($data);
    }
    
    /**
     * Contar intentos fallidos recientes
     */
    public function contarIntentosFallidos(string $username, int $minutos = self::TIEMPO_BLOQUEO_MINUTOS): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM intentos_login
            WHERE username = ?
            AND exitoso = 0
            AND fecha_intento >= DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([$username, $minutos]);
        return (int) $stmt->fetchColumn();
    }
    
    /**
     * Verificar si usuario está bloqueado
     */
    public function estaBloqueado(string $username): bool {
        $intentos = $this->contarIntentosFallidos($username);
        return $intentos >= self::MAX_INTENTOS;
    }
    
    /**
     * Obtener tiempo restante de bloqueo
     */
    public function getTiempoRestanteBloqueo(string $username): ?int {
        $stmt = $this->db->prepare("
            SELECT TIMESTAMPDIFF(MINUTE, NOW(), DATE_ADD(MAX(fecha_intento), INTERVAL ? MINUTE)) as minutos
            FROM intentos_login
            WHERE username = ?
            AND exitoso = 0
            AND fecha_intento >= DATE_SUB(NOW(), INTERVAL ? MINUTE)
        ");
        $stmt->execute([self::TIEMPO_BLOQUEO_MINUTOS, $username, self::TIEMPO_BLOQUEO_MINUTOS]);
        $result = $stmt->fetchColumn();
        return $result > 0 ? (int) $result : null;
    }
    
    /**
     * Limpiar intentos antiguos (mantenimiento)
     */
    public function limpiarIntentosAntiguos(int $dias = 30): int {
        $stmt = $this->db->prepare("
            DELETE FROM intentos_login
            WHERE fecha_intento < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        $stmt->execute([$dias]);
        return $stmt->rowCount();
    }
    
    /**
     * Obtener estadísticas de intentos fallidos
     */
    public function getEstadisticasIntentosFallidos(int $dias = 7): array {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(fecha_intento) as fecha,
                COUNT(*) as total_intentos,
                SUM(CASE WHEN exitoso = 0 THEN 1 ELSE 0 END) as fallidos,
                SUM(CASE WHEN exitoso = 1 THEN 1 ELSE 0 END) as exitosos,
                COUNT(DISTINCT username) as usuarios_distintos,
                COUNT(DISTINCT ip_address) as ips_distintas
            FROM intentos_login
            WHERE fecha_intento >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(fecha_intento)
            ORDER BY fecha DESC
        ");
        $stmt->execute([$dias]);
        return $stmt->fetchAll();
    }
}

