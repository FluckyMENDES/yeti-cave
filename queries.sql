# Добавляем категории товаров
INSERT INTO categories
SET title = 'Доски и лыжи';
INSERT INTO categories
SET title = 'Крепления';
INSERT INTO categories
SET title = 'Ботинки';
INSERT INTO categories
SET title = 'Одежда';
INSERT INTO categories
SET title = 'Инструменты';
INSERT INTO categories
SET title = 'Разное';

# Добавляем пользователей
INSERT INTO users
SET email = 'ignat.v@gmail.com', name = 'Игнат', password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka';
INSERT INTO users
SET email = 'kitty_93@li.ru', name = 'Леночка', password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa';
INSERT INTO users
SET email = 'flucky@mail.ru', name = 'Олег', password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW', avatar = 'img/avatar.jpg';

# Добавляем товары
INSERT INTO lots
SET title = '2014 Rossignol District Snowboard',
    description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.',
    start_price = '10999',
    price_step = '100',
    current_price = '11009',
    img = 'img/lot-1.jpg',
    category = 1,
    create_date = '2020-09-20 17:31:18';

INSERT INTO lots
SET title = 'DC Ply Mens 2016/2017 Snownboard',
    description = 'Описание лота.',
    start_price = '15999',
    price_step = '300',
    current_price = '16299',
    img = 'img/lot-2.jpg',
    category = 1,
    create_date = '2020-08-25 14:19:42';

INSERT INTO lots
SET title = 'Крепления Union Contact Pro 2015 года размер L/XL',
    description = 'Описание лота.',
    start_price = '8000',
    price_step = '150',
    current_price = '8150',
    img = 'img/lot-3.jpg',
    category = 2,
    create_date = '2020-09-03 22:51:37';

INSERT INTO lots
SET title = 'Ботинки для сноуборда DC Mutiny Charcoal',
    description = 'Описание лота.',
    start_price = '10999',
    price_step = '250',
    current_price = '11249',
    img = 'img/lot-4.jpg',
    category = 3,
    create_date = '2020-09-15 19:58:53';

INSERT INTO lots
SET title = 'Куртка для сноуборда DC Mutiny Charcoal',
    description = 'Описание лота.',
    start_price = '7500',
    price_step = '50',
    current_price = '7550',
    img = 'img/lot-5.jpg',
    category = 4,
    create_date = '2020-09-21 10:18:22';

INSERT INTO lots
SET title = 'Маска Oakley Canopy',
    description = 'Описание лота.',
    start_price = '5400',
    price_step = '75',
    current_price = '5475',
    img = 'img/lot-6.jpg',
    category = 6,
    create_date = '2020-08-29 12:28:51';

# Добавляем ставки
INSERT INTO bids
SET bid = '10999', user = '1', lot_id = '1', date = '2020-09-19 12:02:07';
INSERT INTO bids
SET bid = '11099', user = '2', lot_id = '1', date = '2020-09-19 19:18:46';
INSERT INTO bids
SET bid = '11199', user = '3', lot_id = '1', date = '2020-09-20 15:31:52';

# -----------------Запросы----------------
# Получаем все категории
SELECT title FROM categories;
#Получаем самые новые открытые лоты. Название, стартовая цена, ссылка на изображение, текущую цену, кол-во ставок, название категории.
SELECT lots.title, lots.img, lots.current_price, categories.category
FROM lots
JOIN categories
ON lots.category_id = categories.id;
ORDER BY lots.create_date DESC;
#Получаем лот по id вместе с названием категории
SELECT lots.title, categories.title FROM lots
JOIN categories
ON categories.id = lots.category
WHERE lots.id = 1;
#Изменяем название первого лота
UPDATE lots
SET title = 'Новое название'
WHERE id = 1;
#Выводим новые ставки для определенного лота
SELECT bids.date, bids.bid, users.name
FROM bids
JOIN users
ON bids.user = users.id
WHERE bids.lot_id = 1
ORDER BY bids.date DESC;
