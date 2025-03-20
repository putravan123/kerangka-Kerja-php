<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoload

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load(); // Load variabel dari .env

return [
    'driver'   => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_DRIVER'] === 'sqlite' 
                 ? (__DIR__ . '/../storage/database.sqlite') // Path otomatis
                 : ($_ENV['DB_DATABASE'] ?? ''),
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];

