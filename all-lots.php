<?php
session_start();

require_once 'config.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'db/categories.php';

$goods = []; // Обнуляем массив товаров
$excerpt = ''; // Отрывок для SQL-запроса


if ($_GET['category']) { // Если в гет параметрах есть 'category'
    $category = $_GET['category']; // Сохраняем параметр в переменную
    $excerpt = "WHERE categories.eng = '$category'"; // Сохраняем к отрывок SQL-запроса фильтрацию по данной категории

    $sql = "SELECT category FROM categories WHERE categories.eng = :category"; // Запрос в БД для получения названия категории
    $values = ['category' => $category]; // Значения для подготовленного выражения
    $sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
    $sth->execute($values); // Добавляем значения
    $page_title = $sth->fetch(PDO::FETCH_ASSOC)['category']; // Получаем название категории
}

// Пагинация
$cur_page = $_GET['page'] ?? 1; // получаем текущую страницу, если ее нет - устанавливаем первую
$goods_per_page = 3; // Количество товаров на странице

$sql = "SELECT COUNT(*) as cnt FROM lots JOIN categories ON categories.id = lots.category_id $excerpt"; // Запрос в БД для получения количества подходящих лотов
$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
$sth->execute(); // Добавляем значения
$lots_count = $sth->fetch(PDO::FETCH_ASSOC)['cnt']; // Получаем количество подходящих лотов

$pages_count = ceil($lots_count / $goods_per_page); // Вычисляем сколько необходимо страниц
$offset = ($cur_page - 1) * $goods_per_page; // Вычисляем смещение

$pages = range(1, $pages_count); // созаем массив от 1 до кол-ва страниц

// Запрос на получение товаров подходящих по категории
$sql = "SELECT lots.*, categories.category FROM lots
            JOIN categories
            ON categories.id = lots.category_id
            $excerpt
            ORDER BY lots.create_date DESC
            LIMIT $goods_per_page OFFSET $offset;"; // Запрос в БД для получения количества подходящих лотов
$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
$sth->execute(); // Добавляем значения
$goods = $sth->fetchAll(PDO::FETCH_ASSOC); // Получаем массив с лотами

$page_content = render('templates/all-lots.php', ['page_title' => $page_title, 'goods' => $goods, 'categories' => $categories, 'pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
