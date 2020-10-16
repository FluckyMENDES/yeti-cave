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
    $sql = "SELECT COUNT(*) as cnt FROM lots WHERE MATCH(title, description) AGAINST (:search)";
    $values = ['search' => $search]; // Значения для подготовленного выражения
    $sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
    $sth->execute($values); // Добавляем значения
    $items_count = $sth->fetch(PDO::FETCH_ASSOC)['cnt']; // Сохраняем в переменную кол-во элементов

    $pages_count = ceil($items_count / $goods_per_page); // Вычисляем сколько необходимо страниц
    $offset = ($cur_page - 1) * $goods_per_page; // Вычисляем смещение

    $pages = range(1, $pages_count); // созаем массив от 1 до кол-ва страниц

    // Запрос в БД для получения всех лотов соотв. поисковому запросу
    $sql = "SELECT lots.*, categories.category FROM lots
            JOIN categories
            ON categories.id = lots.category_id
            WHERE MATCH(title, description)
            AGAINST (:search) LIMIT $goods_per_page OFFSET $offset;";
    $sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
    $sth->execute($values); // Добавляем значения
    $goods = $sth->fetchAll(PDO::FETCH_ASSOC); // Сохраняем в переменную массив товаров
}

$page_content = render('templates/search.php', ['search' => $search, 'goods' => $goods, 'categories' => $categories, 'pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
