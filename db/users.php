<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

// ===== Проверка подключения к базе данных
if (!$link) { // Не подключились
    $error = mysqli_connect_error();
    $page_content = render('templates/error.php', ['error' => $error]);
} else { // Подключились
    $sql = 'SELECT email FROM users'; // Получаем все e-mail'ы из базы данных
    $users = get_array_from_db($link, $sql); // Сохраняем их в массив
}
