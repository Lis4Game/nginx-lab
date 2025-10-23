<?php
session_start();

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? '';
$course = $_POST['course'] ?? '';
$electronic = isset($_POST['electronic']) ? 'Да' : 'Нет';
$payment = $_POST['payment'] ?? '';

$errors = [];
if (empty($name)) $errors[] = "Имя не может быть пустым";
if (empty($age)) {
    $errors[] = "Возраст не указан";
} elseif (!is_numeric($age) || $age < 1 || $age > 120) {
    $errors[] = "Укажите настоящий возраст (1–120)";
}
if (empty($course)) $errors[] = "Выберите курс";
if (empty($payment)) $errors[] = "Выберите способ оплаты";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$age = htmlspecialchars($age, ENT_QUOTES, 'UTF-8');
$course = htmlspecialchars($course, ENT_QUOTES, 'UTF-8');
$electronic = htmlspecialchars($electronic, ENT_QUOTES, 'UTF-8');
$payment = htmlspecialchars($payment, ENT_QUOTES, 'UTF-8');

$line = implode(";", [$name, $age, $course, $electronic, $payment]) . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

$_SESSION['name'] = $name;
$_SESSION['age'] = $age;
$_SESSION['course'] = $course;
$_SESSION['electronic'] = $electronic;
$_SESSION['payment'] = $payment;

header("Location: index.php");
exit();