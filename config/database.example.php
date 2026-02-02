<?php

/**
 * Singleton Database Connection - ARCHIVO DE EJEMPLO
 * Copie este archivo como database.php y configure sus credenciales
 */
class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;
    
    // CONFIGURAR ESTOS VALORES SEGÚN SU ENTORNO
    private string $host = 'localhost';
    private string $dbname = 'escuela_pablo_neruda';
    private string $username = 'root';
    private string $password = '';  // Por defecto vacío en XAMPP
    private string $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch (PDOException $e) {
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    // Prevenir clonación
    private function __clone() {}
    
    // Prevenir deserialización
    public function __wakeup() {
        throw new Exception("No se puede deserializar un singleton");
    }
}

