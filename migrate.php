<?php
$config = require 'config.php';

try {
    // Koneksi ke MySQL
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Koneksi ke database {$config['database']} berhasil.\n";

    // Ambil semua file di folder migrations
    $files = glob(__DIR__ . './config/migrations/*.php');
    sort($files); // Urutkan berdasarkan nama (timestamp)

    foreach ($files as $file) {
        require_once $file;
        $className = basename($file, '.php');

        if (class_exists($className)) {
            $migration = new $className($pdo);
            $migration->up();
            echo "Migrasi {$className} berhasil dijalankan.\n";
        }
    }

    echo "Semua migrasi selesai.\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
?>
