<?php

require_once 'db.php';

class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $age, $course, $certificate_needed, $payment_form) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO registrations (name, age, course, certificate_needed, payment_form) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $course, $certificate_needed, $payment_form]);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM registrations ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $name) {
        $stmt = $this->pdo->prepare("UPDATE registrations SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM registrations WHERE id=?");
        $stmt->execute([$id]);
    }
}