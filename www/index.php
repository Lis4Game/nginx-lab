

<?php if(isset($_SESSION['errors'])): ?>
    <ul style="color:red;">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['name'])): ?>
    <p>Данные из сессии:</p>
    <ul>
        <li>Имя: <?= htmlspecialchars($_SESSION['name']) ?></li>
        <li>Возраст: <?= htmlspecialchars($_SESSION['age']) ?></li>
        <li>Курс: <?= htmlspecialchars($_SESSION['course']) ?></li>
        <li>Сертификат нужен: <?= htmlspecialchars($_SESSION['certificate_needed'] == 1 ? 'Да' : 'Нет') ?></li> 
        <li>Формат оплаты: <?= htmlspecialchars($_SESSION['payment_form']) ?></li> 
    </ul>

    
<?php else: ?>
    <p>Данных пока нет.</p>
<?php endif; ?>



<?php if (isset($_COOKIE['last_submission'])): ?>
    Последняя отправка формы: <?= htmlspecialchars($_COOKIE['last_submission']) ?><br>
<?php endif; ?>


<a href="form.html">Заполнить форму</a> |
<a href="view.php">Посмотреть все данные</a>

<h2>Зарегистрированные пользователи:</h2>
<ul>
<?php
require_once 'db.php';
require_once 'Student.php';

$student = new Student($pdo);
$all = $student->getAll();

if (empty($all)) {
    echo '<li>Пока никто не зарегистрирован.</li>';
} else {
    foreach ($all as $row) {
        echo '<li>';
        echo htmlspecialchars($row['name']) . ', ';
        echo (int)$row['age'] . ' лет, ';
        echo 'курс: ' . htmlspecialchars($row['course']) . ', ';
        echo 'сертификат: ' . ($row['certificate_needed'] ? 'Да' : 'Нет') . ', ';
        echo 'оплата: ' . htmlspecialchars($row['payment_form']) . ', ';
        echo 'дата: ' . date('d.m.Y H:i:s', strtotime($row['created_at']));
        echo '</li>';
    }
}
?>
</ul>

<h2>Только старше 18 лет:</h2>
<ul>
<?php
$filtered = $student->getFiltered(18);

if (empty($filtered)) {
    echo '<li>Нет пользователей старше 18 лет.</li>';
} else {
    foreach ($filtered as $row) {
        echo '<li>';
        echo htmlspecialchars($row['name']) . ', ';
        echo (int)$row['age'] . ' лет, ';
        echo 'курс: ' . htmlspecialchars($row['course']) . ', ';
        echo 'сертификат: ' . ($row['certificate_needed'] ? 'Да' : 'Нет') . ', ';
        echo 'оплата: ' . htmlspecialchars($row['payment_form']) . ', ';
        echo 'дата: ' . date('d.m.Y H:i:s', strtotime($row['created_at']));
        echo '</li>';
    }
}
?>
</ul>