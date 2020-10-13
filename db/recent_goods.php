<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

// ===== Проверка подключения к базе данных
if (!$link) { // Не подключились
    $error = mysqli_connect_error();
    $page_content = render('templates/error.php', ['error' => $error]);
} else { // Подключились
    // Получаем товары
    $now = date('Y-m-d H:i:s', strtotime('now'));
    $sql = "SELECT lots.*, categories.category
            FROM lots
            JOIN categories
            ON lots.category_id = categories.id
            WHERE lots.end_date > NOW()
            ORDER BY lots.end_date ASC LIMIT 9;";
    $goods = get_array_from_db($link, $sql);
}
