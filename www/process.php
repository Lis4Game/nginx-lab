<?php
session_start();

$name = htmlspecialchars($_POST['name'] ?? '');
$age = htmlspecialchars($_POST['age'] ?? '');

$_SESSION['name'] = $name;
$_SESSION['age'] = $age;

header("Location: index.php");

$line = $name . ";" . $age . "\n"; //<--- строка для записи в файл

file_put_contents("data.txt", $line, FILE_APPEND);

exit();

?>