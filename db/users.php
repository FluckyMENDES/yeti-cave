<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

$sql = "SELECT email FROM users";
$sth = $dbh->prepare($sql);
$sth->execute();
$users = $sth->fetchAll(PDO::FETCH_ASSOC);
