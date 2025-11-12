<?php
session_start();

require_once 'db.php';
require_once 'Student.php';

$student = new Student($pdo);

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$course = $_POST['course'] ?? '';
$certificate_needed = isset($_POST['certificate_needed']) ? 1 : 0;
$payment_form = $_POST['payment_form'] ?? '';

$errors = [];
if (empty($name)) $errors[] = "Имя не может быть пустым";
if (empty($age)) {
    $errors[] = "Возраст не указан";
} elseif (!is_numeric($age) || $age < 1 || $age > 120) {
    $errors[] = "Укажите настоящий возраст (1–120)";
}
if (empty($course)) $errors[] = "Выберите курс";
if (empty($payment_form)) $errors[] = "Выберите способ оплаты";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$age = htmlspecialchars($age, ENT_QUOTES, 'UTF-8');
$course = htmlspecialchars($course, ENT_QUOTES, 'UTF-8');
$payment_form = htmlspecialchars($payment_form, ENT_QUOTES, 'UTF-8');

$student->add($name, $age, $course, $certificate_needed, $payment_form);

$_SESSION['name'] = $name;
$_SESSION['age'] = $age;
$_SESSION['course'] = $course;
$_SESSION['certificate_needed'] = $certificate_needed;
$_SESSION['payment_form'] = $payment_form;

$_SESSION['success'] = "Регистрация прошла успешно!";

header("Location: index.php");
exit();