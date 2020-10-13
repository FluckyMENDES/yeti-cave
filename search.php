<?php
session_start();

$page_title = 'Поиск товаров';

require_once 'config.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'db/categories.php';

$goods = [];

$search = htmlspecialchars($_GET['search']) ?? '';

if ($search) {
    $sql = "SELECT lots.*, categories.category FROM lots
            JOIN categories
            ON categories.id = lots.category_id
            WHERE MATCH(title, description)
            AGAINST ('$search');";
    $result = mysqli_query($link, $sql);
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) { // извлечение ассоциативного массива
            $goods[] = $row;
        }
        mysqli_free_result($result); // удаление выборки
    }

}


$page_content = render('templates/search.php', ['search' => $search, 'goods' => $goods, 'categories' => $categories]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
