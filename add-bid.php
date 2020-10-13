<?php
session_start();
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

$page_title = 'Добавление новой ставки';

if($_SESSION['user']) { // Если пользователь вошел на сайт

    if ($_POST['bid']) { // И он перешел с отправкой формы со ставкой
        $bid = $_POST['bid'];
        settype($bid, 'integer'); // Приводим переменную к числу для исключения возможности SQL-инъекции
        $user_email = $_SESSION['user']['email'];
        $good_id = $_POST['good_id'];
        settype($good_id, 'integer'); // Приводим переменную к числу для исключения возможности SQL-инъекции

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

            mysqli_query($link, 'START TRANSACTION'); // Объединяем два запроса в транзакцию
            $sql1 = "INSERT INTO bids SET amount = $bid, lot_id = $good_id, user_id = (SELECT id FROM users WHERE email = '$user_email'), date = '" . date('Y-m-d H:i:s') . "';";
            $result1 = mysqli_query($link, $sql1);
            $sql2 = "UPDATE lots SET current_price = $bid, winner_id = (SELECT id FROM users WHERE email = '$user_email') WHERE id = $good_id;";
            $result2 = mysqli_query($link, $sql2);

            if ($result1 && $result2) { // если оба запроса выполнились успешно
                mysqli_query($link, "COMMIT"); // сохраняем изменения в БД
            } else {
                mysqli_query($link, "ROLLBACK"); // откатываем изменения
            }

            header("Location: lot.php?id=$good_id");
            exit();
        }
    }

} else { // Пользователь не вошел на сайт
    $page_content = render('templates/login.php');
}

require_once 'db/categories.php';
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
