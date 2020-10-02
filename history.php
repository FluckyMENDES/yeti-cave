<?php
require_once 'config.php';
require_once 'functions.php';
require_once  'data.php';

$page_title = 'История просмотров';


if (isset($_COOKIE['recent_goods'])) { // Если имеется куки
    $goods_ids = json_decode($_COOKIE['recent_goods']); // Получаем в переменную массив с ID из куки
    foreach ($goods_ids as $id) { // Проходимся по всему массиву с ID
        $recent_goods[] = $goods[$id - 1]; // Записываем в массив товары из базы данных с указанным ID
    }
}

$page_content = render('templates/all-lots.php', ['goods' => $recent_goods]); // сохраняем разметку добавления лота в переменную

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'good_categories' => $good_categories]);
