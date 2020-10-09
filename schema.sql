-- Создание базы данных
CREATE DATABASE yeti_cave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

-- Выбираем созданную базу данных
USE yeti_cave;

-- Создаем необходимые таблицы
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_date DATETIME,
    email VARCHAR(50) UNIQUE,
    name VARCHAR(20),
    password VARCHAR(64),
    avatar VARCHAR(255),
    address VARCHAR(100),
    lots INT,
    bids INT
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title CHAR(20)
);

CREATE TABLE bids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME,
    bid INT,
    user INT,
    lot_id INT
);

CREATE TABLE lots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    create_date DATETIME,
    end_date DATETIME,
    title CHAR(255),
    description TEXT,
    img CHAR(255),
    start_price INT,
    current_price INT,
    price_step INT,
    fav_count INT,
    author INT,
    purchaser INT,
    category INT,
    bids_count INT
);
