<?php
session_start();

$name = htmlspecialchars($_POST['name'] ?? '');
$age = htmlspecialchars($_POST['age'] ?? '');

$errors = [];
if(empty($name)) $errors[] = "Имя не может быть пустым";
if(empty($age)) $errors[] = "Возраст не указан";
elseif(!is_numeric($age)) $errors[] = "Некорректно указан возраст";
else {
	if($age<1||$age>120) $errors[] = "Укажите настояций возраст (1-120)";
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}


$_SESSION['name'] = $name;
$_SESSION['age'] = $age;

header("Location: index.php");

$line = $name . ";" . $age . "\n"; //<--- строка для записи в файл

file_put_contents("data.txt", $line, FILE_APPEND);

exit();

?>