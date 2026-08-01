<?php

// Supports both local and cloud (Railway, Render, etc.) environments
$host     = getenv('DB_HOST')     ?: 'localhost';
$port     = getenv('DB_PORT')     ?: '3306';
$name     = getenv('DB_NAME')     ?: 'yii2basic';
$user     = getenv('DB_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

return [
    'class' => \yii\db\Connection::class,
    'dsn' => "mysql:host={$host};port={$port};dbname={$name}",
    'username' => $user,
    'password' => $password,
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
