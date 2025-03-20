<?php
$config = require __DIR__ . '/config/database.php';

try {
    if ($config['driver'] === 'mysql') {
        // Buat koneksi awal tanpa memilih database
        $pdo = new PDO(
            "mysql:host={$config['host']}",
            $config['username'],
            $config['password']
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cek apakah database sudah ada
        $dbName = $config['database'];
        $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
        $databaseExists = $stmt->fetch();

        if (!$databaseExists) {
            echo "⚠️ Database '$dbName' tidak ditemukan. Membuat database baru...\n";
            $pdo->exec("CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            echo "✅ Database '$dbName' berhasil dibuat!\n";
        }

        // Koneksi ulang ke database yang sudah dipastikan ada
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['database']}",
            $config['username'],
            $config['password']
        );

        echo "✅ Berhasil terhubung ke MySQL!\n";
    } elseif ($config['driver'] === 'sqlite') {
        $database = $config['database'];

        if (empty($database)) {
            throw new Exception("Path database SQLite tidak valid!");
        }

        $storageDir = dirname($database);
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        if (!file_exists($database)) {
            file_put_contents($database, '');
            chmod($database, 0666);
        }

        $pdo = new PDO("sqlite:$database");
        echo "✅ Berhasil terhubung ke SQLite!\n";
    } else {
        throw new Exception("Driver database tidak dikenal: {$config['driver']}");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ==========================
    //  📌 JALANKAN MIGRASI
    // ==========================
    $migrationPath = realpath(__DIR__ . '/migrations');

    if (!$migrationPath || !is_dir($migrationPath)) {
        throw new Exception("Folder migrations/ tidak ditemukan.");
    }

    // Ambil semua file PHP di dalam folder migrations/
    $migrationFiles = glob($migrationPath . '/*.php');

    if (empty($migrationFiles)) {
        echo "⚠️ Tidak ada file migrasi ditemukan.\n";
    } else {
        foreach ($migrationFiles as $file) {
            echo "🚀 Menjalankan migrasi: " . basename($file) . "\n";
        
            require_once $file;
            
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $className = preg_replace('/^\d+_\d+_/', '', $filename);
            $className = str_replace('_', '', ucwords($className, '_')); // Konversi ke PascalCase
            
            if (class_exists($className)) {
                $migration = new $className($pdo);
                $migration->up();
            } else {
                echo "❌ Error: Kelas $className tidak ditemukan dalam file " . basename($file) . "\n";
            }
        }
        
        echo "✅ Semua migrasi telah dijalankan.\n";
    }

} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage() . "\n");
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage() . "\n");
}
