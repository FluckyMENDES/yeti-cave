<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/categories.php';

if ($_SESSION['user']['email']) { // Если пользователь не зашел на сайт

    if ($_GET['id']) { // Если не передан get-параметр 'id'

        $lot_id = $_GET['id']; // Сохраняем переданный параметр в переменную
        $user_email = $_SESSION['user']['email']; // Сохраняем текущего пользователя в переменную

        // Запрос В БД на автора данного лота
        $sql = "SELECT users.email AS email FROM lots
        JOIN users
        ON users.id = lots.author_id
        WHERE users.email = :user_email
        AND lots.id = :lot_id";
        $values = ['lot_id' => $lot_id, 'user_email' => $user_email];
        $sth = $dbh->prepare($sql);
        $sth->execute($values);
        $author_email = $sth->fetch(PDO::FETCH_COLUMN); // Сохраняем в переменную автора данного лота

        if ($user_email === $author_email) { // Если автор лота и текущий пользователь это один пользователь
            $sql = "DELETE FROM lots WHERE  `id`=:lot_id"; // Отправляем запрос в БД на удаление лота
            $values = ['lot_id' => $lot_id];
            $sth = $dbh->prepare($sql);
            $sth->execute($values);

            header("Location: index.php"); // Перенаправляем пользователя на главную страницу
            exit(); // Прекращаем выполнение скрипта

        } else {
            $error = 'У вас нет прав на удаление данного лота.';
            $page_content = render('templates/error.php', ['error' => $error, 'categories' => $categories]); // Выводим шаблон страницы ошибки
        }

    } else {
        $error = 'Не указан ID лота.';
        $page_content = render('templates/error.php', ['error' => $error, 'categories' => $categories]); // Выводим шаблон страницы ошибки
    }

} else {
    $error = 'Выполните вход на сайт';
    $page_content = render('templates/error.php', ['error' => $error, 'categories' => $categories]); // Выводим шаблон страницы ошибки
}

echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $error, 'categories' => $categories]);



