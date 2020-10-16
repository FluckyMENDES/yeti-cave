<?php
session_start();

$page_title = 'Главная';

require_once 'config.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'db/categories.php';


// Пагинация
$cur_page = $_GET['page'] ?? 1; // получаем текущую страницу, если ее нет - устанавливаем первую
$goods_per_page = 6; // Количество товаров на странице

$sql = "SELECT COUNT(*) as cnt FROM lots JOIN categories ON categories.id = lots.category_id
        WHERE lots.end_date > NOW();"; // Запрос в БД для получения количества подходящих лотов
$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
$sth->execute(); // Добавляем значения
$lots_count = $sth->fetch(PDO::FETCH_ASSOC)['cnt']; // Получаем количество подходящих лотов

$pages_count = ceil($lots_count / $goods_per_page); // Вычисляем сколько необходимо страниц
$offset = ($cur_page - 1) * $goods_per_page; // Вычисляем смещение

$pages = range(1, $pages_count); // созаем массив от 1 до кол-ва страниц

// Запрос на получение товаров подходящих по категории
$now = date('Y-m-d H:i:s', strtotime('now'));
$sql = "SELECT lots.*, categories.category
            FROM lots
            JOIN categories
            ON lots.category_id = categories.id
            WHERE lots.end_date > NOW()
            ORDER BY lots.end_date ASC
            LIMIT $goods_per_page OFFSET $offset;"; // Запрос в БД для получения количества подходящих лотов
$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
$sth->execute(); // Добавляем значения
$goods = $sth->fetchAll(PDO::FETCH_ASSOC); // Получаем массив с лотами


$page_content = render('templates/index.php', ['goods' => $goods, 'categories' => $categories, 'pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
