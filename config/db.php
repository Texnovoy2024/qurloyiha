<?php

// Supports both local and cloud (Railway, Render, etc.) environments
$host     = getenv('DB_HOST')     ?: getenv('MYSQLHOST')     ?: getenv('MYSQL_HOST')     ?: 'localhost';
$port     = getenv('DB_PORT')     ?: getenv('MYSQLPORT')     ?: getenv('MYSQL_PORT')     ?: '3306';
$name     = getenv('DB_NAME')     ?: getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'yii2basic';
$user     = getenv('DB_USER')     ?: getenv('MYSQLUSER')     ?: getenv('MYSQL_USER')     ?: 'root';
$password = getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';

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
