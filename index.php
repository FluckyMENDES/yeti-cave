<?php
session_start();

$page_title = 'Главная';

require_once 'config.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'db/goods.php';

$page_content = render('templates/index.php', ['goods' => $goods, 'categories' => $categories]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
