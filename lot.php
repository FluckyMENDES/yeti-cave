<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/recent_goods.php';
require_once 'db/categories.php';


$good = null; // Изначально обнуляем массив товара

if (isset($_GET['id'])) { // Если в параметре GET-запроса имеется id
    $good_id = $_GET['id']; // Сохраняем в переменную данный id

    foreach ($goods as $item) { // Проходимся по массиву с товарами
        if ($item['id'] == $good_id) { // Если натыкаемся на товар с ID идентичным как в переданном GET-параметре
            $good = $item; // Устанавливаем этот элемент массива в переменную
            $page_title = $good['title']; // Задаем title для страницы
            break;
        }
    }
}

if (!$good) { // Если не нашли товара с данным ID
    http_response_code(404); // Устанавливаем код ответа 404
}

// ------------ Cookie ---------------

$cookie_name = 'recent_goods'; // Задаем имя куки
$cookie_value = json_encode([$good['id']]); // Значение куки задаем массив закодированный в json
$cookie_expire = strtotime('+30 days'); // Время жизни куки
$cookie_path = '/'; // На какие страницы на сайте распространяется куки

if (isset($_COOKIE['recent_goods'])) { // Если имеется куки с данным именем
    $tmp_arr = json_decode($_COOKIE['recent_goods']); // Парсим строку куки во временный массив
    if (!in_array($good['id'], $tmp_arr)) { // Если во временном массиве ID данного лота
        array_unshift($tmp_arr, $good['id']); // Добавляем в начало массива ID данного лота
    }
    $cookie_value = json_encode($tmp_arr); // Переводим в json этот массив удаляя дубли
}
setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path); // Сохраняем куки
// -----------------------------------


// Получаем разметку главной страницы в переменную
$page_content = render('templates/lot.php', ['good' => $good]);
// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'goods' => $goods, 'categories' => $categories]);
