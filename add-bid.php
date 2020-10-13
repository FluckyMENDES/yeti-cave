<?php
session_start();
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

$page_title = 'Добавление новой ставки';

if($_SESSION['user']) { // Если пользователь вошел на сайт

    if ($_POST['bid']) { // И он перешел с отправкой формы со ставкой
        $bid = $_POST['bid'];
        $user_email = $_SESSION['user']['email'];
        $good_id = $_POST['good_id'];

        // Получаем из БД текущую цену и шаг ставки
        $sql = "SELECT current_price, price_step FROM lots WHERE id = $good_id";
        $result = mysqli_query($link, $sql);
        $prices = mysqli_fetch_assoc($result);

        $min_bid = array_sum($prices); // Суммируем размер минимальной ставки

        if ($bid < $min_bid) { // Если сделаная пользователем ставка меньше минимальо возможной
            http_response_code(403);
            $error = "Минимальная допустимая ставка меньше возможной.";
            $page_content = render('templates/error.php', ['error' => $error]);

            ########## ДОБАВЛЕНИЕ СТАВКИ В БД ##########
        } else {
            // ОБЪЕДИНИТЬ В ТРАНЗАКЦИЮ
            $sql = "INSERT INTO bids SET amount = $bid, lot_id = $good_id, user_id = (SELECT id FROM users WHERE email = '$user_email'), date = '" . date('Y-m-d H:i:s') . "';";
            $result = mysqli_query($link, $sql);
            $sql = "UPDATE lots SET current_price = $bid, winner_id = (SELECT id FROM users WHERE email = '$user_email') WHERE id = $good_id;";
            $result = mysqli_query($link, $sql);
            header("Location: lot.php?id=$good_id");
            exit();
        }
    }

} else { // Пользователь не вошел на сайт
    $page_content = render('templates/login.php');
}

require_once 'db/categories.php';
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
