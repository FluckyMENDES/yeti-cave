<?php
require_once 'functions.php';
require_once 'config.php';
require_once 'init.php';

$sql = "SELECT category, eng FROM categories";
$sth = $dbh->prepare($sql);
$sth->execute();
$categories = $sth->fetchAll();

