<?php
session_start();
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';
require_once 'db/categories.php';

$page_title = 'Добавление новой ставки';

if($_SESSION['user']) { // Если пользователь вошел на сайт

    if ($_POST['bid']) { // И он перешел с отправкой формы со ставкой
        $bid = $_POST['bid'];
        $user_email = $_SESSION['user']['email'];
        $good_id = $_POST['good_id'];

        // Получаем из БД текущую цену и шаг ставки
        $sql = "SELECT current_price, price_step FROM lots WHERE id = :good_id";
        $values = ['good_id' => $good_id];
        $sth = $dbh->prepare($sql);
        $sth->execute($values);
        $prices = $sth->fetch(PDO::FETCH_ASSOC);

        $min_bid = array_sum($prices); // Суммируем размер минимальной ставки

        if ($bid < $min_bid) { // Если сделаная пользователем ставка меньше минимальо возможной
            http_response_code(403);
            $error = "Минимальная допустимая ставка меньше возможной.";
            $page_content = render('templates/error.php', ['error' => $error]);

        ########## ДОБАВЛЕНИЕ СТАВКИ В БД ##########
        } else {

            try { // Пробуем провести транзакцию
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Устанавливаем атрибут для инстанса PDO для отображения возможных ошибок
                $dbh->beginTransaction(); // Открываем транзакцию

                // Запрос на добавление ставки в таблицу со ставками через подготовленное выражение
                $sql = "INSERT INTO bids SET amount = :bid, lot_id = :good_id, user_id = (SELECT id FROM users WHERE email = :user_email), date = '" . date('Y-m-d H:i:s') . "';";
                $values = ['bid' => $bid, 'good_id' => $good_id, 'user_email' => $user_email]; // Передаваемые значения
                $sth = $dbh->prepare($sql); // Отправляем запрос без значений
                $sth->execute($values); // Подставляем значения

                // Запрос на обновление текущей цены в таблице lots
                $sql = "UPDATE lots SET current_price = :bid, winner_id = (SELECT id FROM users WHERE email = :user_email) WHERE id = :good_id;";
                $sth = $dbh->prepare($sql); // Отправляем запрос без значений
                $sth->execute($values); // Подставляем значения

                $dbh->commit(); // Сохраняем изменения

                header("Location: lot.php?id=$good_id"); // Перенаправляем пользователя на страницу лота

            } catch (Exception $e) { // Если транзакция завершилась с ошибкой
                $dbh->rollBack(); // Откатываем все изменения
                echo "Ошибка: " . $e->getMessage(); // Выводим текст ошибки
            }
        }
    }

} else { // Пользователь не вошел на сайт
    $page_content = render('templates/login.php');
}

echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
