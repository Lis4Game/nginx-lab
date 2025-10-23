<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все данные</title>
</head>
<body>
    <h2>Все сохранённые данные:</h2>
    <ul>
        <?php
        if (file_exists("data.txt")) {
            $lines = file("data.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (empty($lines)) {
                echo "<li>Данных нет</li>";
            } else {
                foreach ($lines as $line) {
                    $parts = explode(";", $line);
                    if (count($parts) == 5) {
                        [$name, $age, $course, $electronic, $payment] = $parts;
                        $courseNames = [
                            '1' => '1 курс',
                            '2' => '2 курс',
                            '3' => '3 курс',
                            '4' => '4 курс'
                        ];
                        $courseLabel = $courseNames[$course] ?? "Курс $course";
                        $paymentLabels = [
                            'card' => 'Банковская карта',
                            'sbp' => 'СБП',
                            'cash' => 'Наличные при получении'
                        ];
                        $paymentLabel = $paymentLabels[$payment] ?? $payment;

                        echo "<li><strong>$name</strong>, возраст: $age, курс: $courseLabel, сертификат: $electronic, оплата: $paymentLabel</li>";
                    }
                }
            }
        } else {
            echo "<li>Данных нет</li>";
        }
        ?>
    </ul>
    <a href="index.php">На главную</a>
</body>
</html>