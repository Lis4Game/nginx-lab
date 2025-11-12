

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