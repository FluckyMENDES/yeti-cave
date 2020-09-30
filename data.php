<?php
// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) .' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) .' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) .' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];
$good_categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$goods = [
    ['title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'current_price' => 10999,
        'price_step' => 100,
        'image_url' => 'img/lot-1.jpg',
        'description' => 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.'],
    ['title' => 'DC Ply Mens 2016/2017 Snownboard',
        'category' => 'Доски и лыжи',
        'current_price' => 15999,
        'price_step' => 300,
        'image_url' => 'img/lot-2.jpg',
        'description' => 'Описание лота'],
    ['title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'current_price' => 8000,
        'price_step' => 150,
        'image_url' => 'img/lot-3.jpg',
        'description' => 'Описание лота'],
    ['title' => 'Ботинки для сноуборда DC Mutiny Charcoal',
        'category' => 'Ботинки',
        'current_price' => 10999,
        'price_step' => 250,
        'image_url' => 'img/lot-4.jpg',
        'description' => 'Описание лота'],
    ['title' => 'Куртка для сноуборда DC Mutiny Charcoal',
        'category' => 'Одежда',
        'current_price' => 7500,
        'price_step' => 50,
        'image_url' => 'img/lot-5.jpg',
        'description' => 'Описание лота'],
    ['title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'current_price' => 5400,
        'price_step' => 75,
        'image_url' => 'img/lot-6.jpg',
        'description' => 'Описание лота'],
];

for ($i = 0, $length = count($goods); $i < $length; $i++) {
    $goods[$i]['id'] = $i + 1;
    $goods[$i]['link'] = 'lot.php?good_id=' . $goods[$i]['id'];
}
