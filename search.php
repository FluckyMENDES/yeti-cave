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

    $cur_page = $_GET['page'] ?? 1; // получаем текущую страницу, если ее нет - устанавливаем первую
    $goods_per_page = 3; // Количество товаров на странице

    // Запрос к БД для получения общего кол-ва товаров подходящих по поисковому запросу
    $sql = "SELECT COUNT(*) as cnt FROM lots
            WHERE MATCH(title, description)
            AGAINST ('$search')";
    $result = mysqli_query($link, $sql);
    $items_count = mysqli_fetch_assoc($result)['cnt']; // Сохраняем в переменную кол-во элементов

    $pages_count = ceil($items_count / $goods_per_page); // Вычисляем сколько необходимо страниц
    $offset = ($cur_page - 1) * $goods_per_page; // Вычисляем смещение

    $pages = range(1, $pages_count); // созаем массив от 1 до кол-ва страниц

    $sql = "SELECT lots.*, categories.category FROM lots
            JOIN categories
            ON categories.id = lots.category_id
            WHERE MATCH(title, description)
            AGAINST ('$search') LIMIT $goods_per_page OFFSET $offset;";
    $result = mysqli_query($link, $sql);
    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) { // извлечение ассоциативного массива
            $goods[] = $row;
        }
        mysqli_free_result($result); // удаление выборки
    }
}

$page_content = render('templates/search.php', ['search' => $search, 'goods' => $goods, 'categories' => $categories, 'pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
