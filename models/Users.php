<?php
require_once 'config/database.php';

class Users {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function insert($data) {
        global $pdo;
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $stmt = $pdo->prepare("INSERT INTO Users ($columns) VALUES ($placeholders)");
        return $stmt->execute(array_values($data));
    }

    public static function update($id, $data) {
        global $pdo;
        $sets = implode(", ", array_map(fn($key) => "$key = ?", array_keys($data)));
        $stmt = $pdo->prepare("UPDATE Users SET $sets WHERE id = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}