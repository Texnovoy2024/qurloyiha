<?php

/**
 * Startup script for container initialization (Railway / Docker).
 * Creates necessary runtime/asset directories and automatically imports the database.
 */

// 1. Ensure runtime and web/assets directories exist with writable permissions
$dirs = [
    __DIR__ . '/runtime',
    __DIR__ . '/web/assets',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (!mkdir($dir, 0777, true) && !is_dir($dir)) {
            echo "Failed to create directory: {$dir}\n";
        }
    }
    @chmod($dir, 0777);
}

echo "Directories verified: runtime/ and web/assets/\n";

// 2. Database Connection & Import Setup
$host     = getenv('DB_HOST')     ?: getenv('MYSQLHOST')     ?: getenv('MYSQL_HOST')     ?: 'localhost';
$port     = getenv('DB_PORT')     ?: getenv('MYSQLPORT')     ?: getenv('MYSQL_PORT')     ?: '3306';
$name     = getenv('DB_NAME')     ?: getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'railway';
$user     = getenv('DB_USER')     ?: getenv('MYSQLUSER')     ?: getenv('MYSQL_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';

echo "Connecting to MySQL at {$host}:{$port} (Database: {$name})...\n";

$maxTries = 10;
$connected = false;
$pdo = null;

for ($i = 1; $i <= $maxTries; $i++) {
    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
        ]);
        $connected = true;
        echo "Successfully connected to MySQL database!\n";
        break;
    } catch (Exception $e) {
        echo "Database connection attempt {$i}/{$maxTries} failed: " . $e->getMessage() . "\n";
        sleep(2);
    }
}

if ($connected && $pdo) {
    try {
        // Check if database tables already exist
        $check = $pdo->query("SHOW TABLES LIKE 'user'");
        if ($check->rowCount() === 0) {
            echo "Database is empty. Importing initial database dump...\n";
            $sqlFile = __DIR__ . '/database/yii2basic.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $pdo->exec($sql);
                echo "Database schema and initial data imported successfully!\n";
            } else {
                echo "SQL dump file not found at: {$sqlFile}\n";
            }
        } else {
            echo "Database tables already exist. Skipping import.\n";
        }
    } catch (Exception $e) {
        echo "Database import notification: " . $e->getMessage() . "\n";
    }
} else {
    echo "Warning: Could not establish DB connection during startup script. PHP server will start anyway.\n";
}
