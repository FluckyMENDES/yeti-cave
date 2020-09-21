<?php

require_once 'config.php';
$is_auth = (bool) rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';


require_once 'functions.php';
require_once 'data.php';

// Получаем разметку главной страницы в переменную
$page_content = render('templates/index.php', ['goods' => $goods]);
// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
echo render('templates/layout.php', ['goods' => $goods, 'good_categories' => $good_categories, 'page_content' => $page_content,
    'is_auth' => $is_auth, 'user_name' => $user_name, 'user_avatar' => $user_avatar]);

