<?php
require_once 'functions.php';
require_once 'config.php';

try {
    $dbh = new PDO($dsn, $db['login'], $db['password']);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

