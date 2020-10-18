<?php
session_start();

require_once 'config.php';
require_once 'init.php';
require_once 'functions.php';
require_once 'db/categories.php';
require_once 'vendor/autoload.php';

$page_title = 'Мои ставки';
$user_id = $_SESSION['user']['id'];

// Запрос на получение всех ставок сделанных текущим пользователем
$sql = "SELECT lots.id as lot_id, lots.winner_id, lots.img, lots.title, lots.end_date, categories.category, bids.amount, bids.date FROM bids
        JOIN users
        ON users.id = bids.user_id
        JOIN lots
        ON bids.lot_id = lots.id
        JOIN categories
        ON categories.id = lots.category_id
        WHERE bids.user_id = :user_id
        ORDER BY bids.date DESC;";
$values = ['user_id' => $user_id]; // Значения для подготовленного выражения
$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
$sth->execute($values); // Добавляем значения
$bids = $sth->fetchAll(); // Сохраняем в массив все ставки пользователя

//$sql = "SELECT users.address, users.email, lots.id, lots.author_id, lots.winner_id FROM users
//        JOIN lots
//        ON lots.author_id = users.id
//        WHERE lots.winner_id = :user_id AND lots.end_date < NOW()";
//$sth = $dbh->prepare($sql); // Отправляем подготовленное выражение в БД
//$sth->execute($values); // Добавляем значения
//$contacts = $sth->fetchAll(PDO::FETCH_ASSOC);

$page_content = render('templates/my-lots.php', ['page_title' => $page_title, 'bids' => $bids, 'contacts' => $contacts, 'categories' => $categories, 'pages' => $pages, 'pages_count' => $pages_count, 'cur_page' => $cur_page]);

// Выводим разметку лейаута, передаем туда разметку главной и необходимые переменные;
$layout_content = render('templates/layout.php', ['page_title' => $page_title, 'categories' => $categories, 'page_content' => $page_content]);

echo $layout_content;
