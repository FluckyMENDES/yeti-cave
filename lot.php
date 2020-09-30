<?php

require_once 'config.php';
require_once 'functions.php';
require_once 'data.php';

$good = null; // Изначально обнуляем массив товара

if (isset($_GET['good_id'])) { // Если в параметре GET-запроса имеется good_id
    $good_id = $_GET['good_id']; // Сохраняем в переменную данный good_id

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


$is_auth = (bool) rand(0, 1);
$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

// Получаем разметку главной страницы в переменную
$page_content = render('templates/lot.php', ['good' => $good]);
// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'goods' => $goods, 'good_categories' => $good_categories,
    'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar]);
