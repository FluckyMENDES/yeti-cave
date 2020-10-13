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
    settype($good_id, 'integer'); // Приводим переменную к числу для исключения возможности SQL-инъекции


    $sql = "SELECT lots.id, lots.create_date, lots.end_date, lots.title, lots.description, lots.img, lots.start_price, lots.current_price, lots.price_step , categories.category, users.name, users.email
            FROM lots
            JOIN categories
            JOIN users
            ON lots.category_id = categories.id AND lots.author_id = users.id
            WHERE lots.id = $good_id;";

    $result = mysqli_query($link, $sql);
    $good = mysqli_fetch_assoc($result);
    $page_title = $good['title'];

    $bids = []; // Создаем массив для ставок лота
    $sql = "SELECT bids.date, bids.amount, users.name
            FROM bids
            JOIN users
            ON users.id = bids.user_id
            WHERE lot_id = $good_id
            ORDER BY bids.date DESC
            LIMIT 10;";

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
$page_content = render('templates/lot.php', ['good' => $good, 'bids' => $bids, 'categories' => $categories]);
// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'goods' => $goods, 'categories' => $categories]);
