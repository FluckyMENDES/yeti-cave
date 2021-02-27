<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/categories.php';
require_once 'vendor/autoload.php';

// Получаем данные всех лотов у которых истек срок и они не имеют флага 'is_closed'
$sql = "SELECT users.name, users.email, lots.title, lots.id FROM lots JOIN users ON users.id = lots.winner_id WHERE end_date < NOW() AND lots.is_closed IS NULL";
$sth = $dbh->prepare($sql);
$sth->execute();
$closed_lots = $sth->fetchAll(PDO::FETCH_ASSOC);


foreach ($closed_lots as $lot) {
    $winner_name = $lot['name'];
    $lot_id = $lot['id'];
    $lot_title = $lot['title'];

    // Create a message
    // $message = (new Swift_Message('Ваша ставка победила'))
    //     ->setFrom(['phpmailphp@yandex.ru' => 'YetiCave: Интернет-аукцион'])
    //     ->setTo([$lot['email']])
    //     ->setBody("<h1>Поздравляем с победой</h1>
    //                 <p>Здравствуйте, $winner_name</p>
    //                 <p>Ваша ставка для лота <a href=\"http://yeti-cave/lot.php?id=$lot_id\">$lot_title</a> победила.</p>
    //                 <p>Перейдите по ссылке <a href=\"http://yeti-cave/my-lots.php\">Мои ставки</a>, чтобы связаться с автором объявления.</p>

    //                 <small>Интернет аукцион \"YetiCave\"</small>", 'text/html');

    // $result = $mailer->send($message); // Отправляем сообщение

    if ($result) { // Если оно отправлено
        $sql = "UPDATE lots SET is_closed = 1 WHERE id = :lot_id"; // Устанавливаем флаг 'is_closed' на 1
        $sth = $dbh->prepare($sql);
        $values = ['lot_id' => $lot_id];
        $sth->execute($values);
    }
}
