<?php
require_once 'functions.php';
require_once 'config.php';

if (isset($db)) {
    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link, 'utf8');

    $categories = [];
    $page_content = '';
}

