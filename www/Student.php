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
        $stmt = $this->pdo->query("SELECT * FROM registrations ORDER BY created_at DESC");
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
	//метод для шрафного задания для фильра 18+ qwq
	public function getFiltered($minAge = 18) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM registrations 
            WHERE age > ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$minAge]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	//метод для шрафного задания для кол-во записей qwq

	    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM registrations");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}