<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'vendor/autoload.php';

try {
    $dbh = new PDO($dsn, $db['login'], $db['password']);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

// Create the Transport
$transport = (new Swift_SmtpTransport('ssl://smtp.yandex.ru', 465))
    ->setUsername('phpmailphp')
    ->setPassword('zZ12345678')
;

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

