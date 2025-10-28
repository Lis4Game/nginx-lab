<?php session_start();
$cacheFile = 'api_cache.json';
$cacheTtl = 300; // 5 минут
$url = 'https://api.github.com/search/repositories?q=topic:php&sort=stars&per_page=5'; 
if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTtl) {
    $cached = json_decode(file_get_contents($cacheFile), true);
	
    if (json_last_error() !== JSON_ERROR_NONE) {
        $cached = ['error' => 'Ошибка чтения кеша'];
    }
    $_SESSION['api_data'] = $cached;
} else {
    try {
        require_once 'ApiClient.php';
        $api = new ApiClient();
        $apiData = $api->request($url);
        
        file_put_contents($cacheFile, json_encode($apiData, JSON_UNESCAPED_UNICODE));
        $_SESSION['api_data'] = $apiData;
    } catch (Exception $e) {
        
        $errorData = ['error' => $e->getMessage()];
        file_put_contents($cacheFile, json_encode($errorData, JSON_UNESCAPED_UNICODE));
        $_SESSION['api_data'] = $errorData;
    }
} 
?>
<?php require_once 'UserInfo.php'; ?>
<?php $info = UserInfo::getInfo(); ?>

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
	<br>
	<button onclick="refreshApi()">Обновить данные</button>

	<script>
	function refreshApi() {
		fetch('refresh_api.php')
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					location.reload();
				} else {
					alert('Ошибка: ' + (data.message || 'Не удалось обновить'));
				}
			})
			.catch(() => alert('Нет связи с сервером'));
	}
	</script>
<?php else: ?>
    <p>Данных пока нет.</p>
<?php endif; ?>

<h3>Информация о пользователе:</h3>
<?php foreach ($info as $key => $val): ?>
    <?= htmlspecialchars($key) ?>: <?= htmlspecialchars($val) ?><br>
<?php endforeach; ?>

<?php if (isset($_COOKIE['last_submission'])): ?>
    Последняя отправка формы: <?= htmlspecialchars($_COOKIE['last_submission']) ?><br>
<?php endif; ?>


<a href="form.html">Заполнить форму</a> |
<a href="view.php">Посмотреть все данные</a>