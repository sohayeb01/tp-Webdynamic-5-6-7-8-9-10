<?php
// Simple database configuration using environment variables
// Works with Railway out of the box, no external dependencies needed

function loadEnvFile($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Load .env file for local development only
if (!isset($_ENV['RAILWAY_ENVIRONMENT'])) {
    loadEnvFile(__DIR__ . '/.env');
}

function getDbConnection() {
    // Railway PostgreSQL service provides these variables automatically
    // Use Railway's provided variables or fallback to custom ones
    $host = $_ENV['PGHOST'] ?? $_ENV['DB_HOST'] ?? getenv('PGHOST') ?: getenv('DB_HOST') ?: 'localhost';
    $port = $_ENV['PGPORT'] ?? $_ENV['DB_PORT'] ?? getenv('PGPORT') ?: getenv('DB_PORT') ?: 5432;
    $dbname = $_ENV['PGDATABASE'] ?? $_ENV['DB_NAME'] ?? getenv('PGDATABASE') ?: getenv('DB_NAME') ?: 'tp_10';
    $username = $_ENV['PGUSER'] ?? $_ENV['DB_USERNAME'] ?? getenv('PGUSER') ?: getenv('DB_USERNAME') ?: 'postgres';
    $password = $_ENV['PGPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? getenv('PGPASSWORD') ?: getenv('DB_PASSWORD') ?: '';
    
    // Railway also provides DATABASE_URL for convenience
    if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
        try {
            $pdo = new PDO($_ENV['DATABASE_URL']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            // Fall through to individual connection parameters
            error_log("DATABASE_URL connection failed: " . $e->getMessage());
        }
    }
    
    try {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Alternative approach using a configuration class
class DatabaseConfig {
    private static $config = null;
    
    public static function getConfig() {
        if (self::$config === null) {
            self::$config = [
                'host' => $_ENV['PGHOST'] ?? $_ENV['DB_HOST'] ?? getenv('PGHOST') ?: getenv('DB_HOST') ?: 'localhost',
                'port' => $_ENV['PGPORT'] ?? $_ENV['DB_PORT'] ?? getenv('PGPORT') ?: getenv('DB_PORT') ?: 5432,
                'dbname' => $_ENV['PGDATABASE'] ?? $_ENV['DB_NAME'] ?? getenv('PGDATABASE') ?: getenv('DB_NAME') ?: 'tp_10',
                'username' => $_ENV['PGUSER'] ?? $_ENV['DB_USERNAME'] ?? getenv('PGUSER') ?: getenv('DB_USERNAME') ?: 'postgres',
                'password' => $_ENV['PGPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? getenv('PGPASSWORD') ?: getenv('DB_PASSWORD') ?: ''
            ];
        }
        return self::$config;
    }
    
    public static function getConnection() {
        $config = self::getConfig();
        
        // Try DATABASE_URL first (Railway convenience)
        if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
            try {
                $pdo = new PDO($_ENV['DATABASE_URL']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch(PDOException $e) {
                error_log("DATABASE_URL connection failed: " . $e->getMessage());
            }
        }
        
        // Fallback to individual parameters
        try {
            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}

// Usage examples:
// $pdo = getDbConnection();
// or
// $pdo = DatabaseConfig::getConnection();
?>