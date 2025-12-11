<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

header('Content-Type: text/html; charset=utf-8');
echo "<h1>Лабораторная №6 — Вариант 17</h1>";
echo "<p><strong>Тема:</strong> Блог на Elasticsearch: индексация, поиск, кеширование и аналитика</p>";


echo "<h2>Redis — Кеширование поста блога</h2>";
$redis = new RedisExample();
$blogPost = json_encode([
    'title' => 'Знакомство с Elasticsearch',
    'content' => 'Elasticsearch — мощный движок полнотекстового поиска.',
    'created_at' => '2025-12-12'
]);
$redis->setValue('post:1', $blogPost);
echo "Записано в кеш: " . htmlspecialchars($blogPost) . "<br>";
echo "Прочитано из кеша: " . htmlspecialchars($redis->getValue('post:1')) . "<br><br>";


echo "<h2>Elasticsearch — Поиск по блогу</h2>";
$elastic = new ElasticExample();
$elastic->indexDocument('blog_posts', '1', [
    'title' => 'Знакомство с Elasticsearch',
    'content' => 'Elasticsearch — мощный движок полнотекстового поиска.',
    'created_at' => '2025-12-12'
]);
$result = $elastic->search('blog_posts', 'content', 'Elasticsearch');
$hits = $result['hits']['hits'] ?? [];
echo "Найдено постов по запросу «Elasticsearch»: " . count($hits) . "<br><br>";


echo "<h2>ClickHouse — Аналитика блога</h2>";
$click = new ClickhouseExample();
$click->execute("CREATE DATABASE IF NOT EXISTS blog");
$click->execute("DROP TABLE IF EXISTS blog.posts");
$click->execute("
    CREATE TABLE blog.posts (
        id UInt32,
        title String,
        created_at Date
    ) ENGINE = MergeTree() ORDER BY id
");
$click->execute("INSERT INTO blog.posts VALUES (1, 'Знакомство с Elasticsearch', '2025-12-12')");
$count = trim($click->execute("SELECT count(*) FROM blog.posts"));
echo "Постов в аналитике: $count<br>";

echo "<hr><p>Все сервисы настроены для блога. Проверьте также:</p>";
echo "<ul>
  <li><a href='/'>Главная</a></li>
  <li><a href='http://localhost:8082' target='_blank'>Redis Commander (кеширование)</a></li>
  <li><a href='http://localhost:9200' target='_blank'>Elasticsearch (индекс blog_posts)</a></li>
  <li><a href='http://localhost:8123/?query=SELECT+1' target='_blank'>ClickHouse (аналитика блога)</a></li>
</ul>";
?>