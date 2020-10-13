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

    $sql = "SELECT lots.*, categories.category
            FROM lots
            JOIN categories
            ON lots.category_id = categories.id
            WHERE lots.id = $good_id";
    $result = mysqli_query($link, $sql);
    $good = mysqli_fetch_assoc($result);
    $page_title = $good['title'];

    $bids = []; // Создаем массив для ставок лота
    $sql = "SELECT bids.date, bids.amount, users.name FROM bids JOIN users ON users.id = bids.user_id WHERE lot_id = $good_id LIMIT 10;";

    if ($result = mysqli_query($link, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) { // извлечение ассоциативного массива
            $bids[] = $row;
        }
        mysqli_free_result($result); // удаление выборки
    }
} else {
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
$page_content = render('templates/lot.php', ['good' => $good, 'bids' => $bids]);
// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'goods' => $goods, 'categories' => $categories]);
