<?php
$page_title = 'Главная';

require_once 'config.php';
require_once 'functions.php';
require_once 'data.php';

$is_auth = (bool) rand(0, 1);
$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

// Получаем разметку главной страницы в переменную
$page_content = render('templates/index.php', ['goods' => $goods]);
// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
echo render('templates/layout.php', ['page_title' => $page_title, 'goods' => $goods, 'good_categories' => $good_categories, 'page_content' => $page_content,
    'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar]);

