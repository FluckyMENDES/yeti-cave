<?php
$page_title = 'Главная';

require_once 'config.php';
require_once 'functions.php';
require_once 'data.php';

session_start();

// Получаем разметку главной страницы в переменную
$page_content = render('templates/index.php', ['goods' => $goods]);
// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'good_categories' => $good_categories, 'page_content' => $page_content]);

echo $layout_content;

