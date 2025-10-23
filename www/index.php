<?php session_start(); ?>

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
        <li>Сертификат нужен: <?= htmlspecialchars($_SESSION['electronic']) ?></li>
        <li>Формат оплаты: <?= htmlspecialchars($_SESSION['payment']) ?></li>
    </ul>

    <?php if (isset($_SESSION['api_data']) && empty($_SESSION['api_data']['error'])): ?>
    <h3>Курсы:</h3>
    <?php foreach ($_SESSION['api_data'] as $category => $courses): ?>
        <?php if (!is_array($courses)) continue; // пропускаем не-массивы (например, метаданные) ?>
        <h4><?= htmlspecialchars(ucfirst($category)) ?>:</h4>
        <ul>
            <?php foreach ($courses as $course): ?>
                <li>
                    <strong><?= htmlspecialchars($course['name'] ?? 'Без названия') ?></strong>  
                    — <?= htmlspecialchars($course['description'] ?? '') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endforeach; ?>
<?php elseif (isset($_SESSION['api_data']['error'])): ?>
    <p style="color:orange;">Ошибка API: <?= htmlspecialchars($_SESSION['api_data']['error']) ?></p>
<?php endif; ?>

<?php else: ?>
    <p>Данных пока нет.</p>
<?php endif; ?>

<a href="form.html">Заполнить форму</a> |
<a href="view.php">Посмотреть все данные</a>