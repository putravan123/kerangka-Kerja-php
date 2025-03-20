<?php
$config = require __DIR__ . '/config/database.php';

try {
    if ($config['driver'] === 'mysql') {
        // Koneksi ke MySQL
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
        echo "✅ Berhasil terhubung ke MySQL!\n";
    } elseif ($config['driver'] === 'sqlite') {
        // Koneksi ke SQLite
        $database = $config['database'];

        // Jika file database belum ada, buat otomatis
        if (!file_exists($database)) {
            touch($database);
        }

        $pdo = new PDO("sqlite:$database");
        echo "✅ Berhasil terhubung ke SQLite!\n";
    } else {
        throw new Exception("Driver database tidak dikenal: {$config['driver']}");
    }

    // Set mode error agar bisa menangkap kesalahan
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage() . "\n");
}
?>
