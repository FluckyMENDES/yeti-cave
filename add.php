<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/categories.php';

$page_title = 'Добавление нового лота';

if (!$_SESSION['user']) {
    http_response_code(403);
    $error = 403;
    $page_content = render('templates/error.php', ['error' => $error]);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Если на страницу перешли с переданными POST-параметрами, т.е. форма отправлена
        $good = $_POST;

        // ---------- ВАЛИДАЦИЯ ФОРМЫ ----------
        // Атрибут name полей для валидации
        $required_fields = ['title', 'category', 'description', 'start_price', 'price_step', 'img', 'end_date'];
        // Ассоциативный массив для показа списка ошибок
        $dict = ['title' => 'Наименование', 'category' => 'Категория', 'description' => 'Описание', 'start_price' => 'Начальная цена', 'price_step' => 'Шаг ставки', 'img' => 'Изображение', 'end_date' => 'Дата окончания торгов'];

        $errors = []; // Массив с ошибками

        foreach ($_POST as $key => $value) { // Проходимся по массиву $_POST
            if (in_array($key, $required_fields)) { // если в массиве с обязательными полями находится ключ из массива $_POST
                if (!$value) { // и у этого ключа отсутствует значение
                    $errors[$dict[$key]] = 'Это поле необходимо заполнить'; // Добавляем ошибку конкретного поля в массив с ошибками
                }
            }

            if ($key === 'start_price') { // Валидация цены
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$dict[$key]] = 'Только цифровое значение';
                }
            } elseif ($key === 'price_step') { // Валидация шага ставки
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$dict[$key]] = 'Только цифровое значение';
                }
            } elseif ($key === 'end_date') { // Валидация даты
                $ts = strtotime($good['end_date']); // Конвертируем дату переданную пользователем в timestamp
                if ($ts < strtotime('now')) { // Если данная дата меньше текущей
                    $errors[$dict[$key]] = 'Введите корректную дату. Не раньше чем завтра.'; // Добавляем соотв. ошибку
                }
            }
        }

        // Валидация файла
        if (isset($_FILES['img'])) {
            $tmp_name = $_FILES['img']['tmp_name'];
            $path = 'img/upload/' . $_FILES['img']['name'];

            $file_size = $_FILES['img']['size'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);


            if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
                $errors['Изображение'] = 'Загрузите картинку в формате JPEG, PNG';
            } else {
                move_uploaded_file($tmp_name, $path); // перемещаем загруженный файл в директорию
                $good['img'] = $path; // добавляем в массив $_POST значение по ключу image_url
            }

            if ($file_size > 200000) {
                $errors['Изображение'] = 'Максимальный размер файла 200кб';
            }
        } else {
            $errors['Изображение'] = 'Вы не загрузили файл';
        }

        if (count($errors)) { // Если в массиве с ошибками имеются ошибки
            $page_content = render('templates/add-lot.php', ['good' => $good, 'errors' => $errors, 'categories' => $categories]);

        // ########## ДОБАВЛЯЕМ ЛОТ В БАЗУ ДАННЫХ ##########

        } else { // Если ошибок нет
            // Формирование запроса к БД
            $user_email = $_SESSION['user']['email'];
            $sql = "INSERT INTO lots (title, category_id, description, start_price, price_step, create_date, img, end_date, current_price, author_id)
                    VALUES ('" . $good['title'] .
                "' ,(SELECT id FROM categories WHERE category = '" . $good['category'] . "'), '" .
                $good['description'] . "', " .
                $good['start_price'] . ", " .
                $good['price_step'] . ", '" .
                date('Y-m-d H:i:s') . "', '".
                $good['img'] . "', '" .
                $good['end_date'] . "', " .
                $good['start_price'] . ", " .
                "(SELECT id FROM users WHERE email = '$user_email')" .
                ");"; // Формируем SQL запрос
            $result = mysqli_query($link, $sql); // Посылаем запрос на запись данных пользователя в БД
            $good_id = mysqli_insert_id($link);

            header('Location: lot.php?id=' . $good_id);
        }

    } else {
        $page_content = render('templates/add-lot.php', ['categories' => $categories]); // сохраняем разметку добавления лота в переменную
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
