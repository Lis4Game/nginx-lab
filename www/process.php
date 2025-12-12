<?php
session_start();

require_once 'db.php';
require_once 'Student.php';

$student = new Student($pdo);

$name = trim($_POST['name'] ?? '');
$age = trim($_POST['age'] ?? '');
$course = trim($_POST['course'] ?? '');
$certificate_needed = isset($_POST['certificate_needed']) ? 1 : 0;
$payment_form = trim($_POST['payment_form'] ?? '');

$errors = [];

if ($name === '') $errors[] = "Имя не может быть пустым";
if ($age === '') {
    $errors[] = "Возраст не указан";
} else {
    $ageInt = (int)$age;
    if ($ageInt < 1 || $ageInt > 120 || (string)$ageInt !== $age) {
        $errors[] = "Укажите настоящий возраст (1–120)";
    }
}
if ($course === '') $errors[] = "Выберите курс";
if ($payment_form === '') $errors[] = "Выберите способ оплаты";

$allowedPayments = ['online', 'cash', 'invoice'];
if (!in_array($payment_form, $allowedPayments)) {
    $errors[] = "Недопустимый способ оплаты";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$student->add($name, $ageInt, $course, $certificate_needed, $payment_form);

$_SESSION['name'] = $name;
$_SESSION['age'] = $ageInt;
$_SESSION['course'] = $course;
$_SESSION['certificate_needed'] = $certificate_needed;
$_SESSION['payment_form'] = $payment_form;

$_SESSION['success'] = "Регистрация прошла успешно!";

header("Location: index.php");
exit();