<?php

namespace App\Services;

use PDO;

class Database {
    private $dbConfig;
    private $connection;

    public function __construct() {
        $this->dbConfig = require __DIR__ . '/../../data/config.php';
        $this->connect();
    }

    private function connect() {
        try {
            $this->connection = new PDO("sqlite:" . $this->dbConfig['db']['path']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }

    public function get_notes() {
        $stmt = $this->connection->prepare("SELECT * FROM notes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_note_by_id(int $id) {
        $stmt = $this->connection->prepare("SELECT * FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add_note(string $title, string $content) {
        $stmt = $this->connection->prepare("INSERT INTO notes (title, content) VALUES (:title, :content)");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();
        $lastInsertId = $this->connection->lastInsertId();
        return $this->get_note_by_id($lastInsertId);
    }

    public function delete_note_by_id(int $id) {
        $stmt = $this->connection->prepare("DELETE FROM notes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function edit_note_by_id(int $id, string $title, string $content) {
        $stmt = $this->connection->prepare("UPDATE notes SET title = :title, content = :content WHERE id = :id");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->get_note_by_id($id);
    }
}
