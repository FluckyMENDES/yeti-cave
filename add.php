<?php
require_once 'config.php';
require_once 'functions.php';
require_once  'data.php';

session_start();

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
        $required_fields = ['title', 'category', 'description', 'current_price', 'price_step', 'image_url', 'end_date'];
        // Ассоциативный массив для показа списка ошибок
        $dict = ['title' => 'Наименование', 'category' => 'Категория', 'description' => 'Описание', 'current_price' => 'Начальная цена', 'price_step' => 'Шаг ставки', 'image_url' => 'Изображение', 'end_date' => 'Дата окончания торгов'];

        $errors = []; // Массив с ошибками

        foreach ($_POST as $key => $value) { // Проходимся по массиву $_POST
            if (in_array($key, $required_fields)) { // если в массиве с обязательными полями находится ключ из массива $_POST
                if (!$value) { // и у этого ключа отсутствует значение
                    $errors[$dict[$key]] = 'Это поле необходимо заполнить'; // Добавляем ошибку конкретного поля в массив с ошибками
                }
            }

            if ($key === 'current_price') {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$dict[$key]] = 'Только цифровое значение';
                }
            } elseif ($key === 'price_step') {
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    $errors[$dict[$key]] = 'Только цифровое значение';
                }
            }
        }


        // Валидация файла
        if (isset($_FILES['image_url'])) {
            $tmp_name = $_FILES['image_url']['tmp_name'];
            $path = 'img/upload/' . $_FILES['image_url']['name'];

            $file_size = $_FILES['image_url']['size'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);


            if ($file_type !== 'image/jpeg') {
                $errors['Изображение'] = 'Загрузите картинку в формате JPEG, PNG';
            } else {
                move_uploaded_file($tmp_name, $path); // перемещаем загруженный файл в директорию
                $good['image_url'] = $path; // добавляем в массив $_POST значение по ключу image_url
            }

            if ($file_size > 200000) {
                $errors['Изображение'] = 'Максимальный размер файла 200кб';
            }
        } else {
            $errors['Изображение'] = 'Вы не загрузили файл';
        }

        if (count($errors)) { // Если в массиве с ошибками имеются ошибки
            $page_content = render('templates/add-lot.php', ['good' => $good, 'errors' => $errors]);
        } else { // Если ошибок нет
            $page_content = render('templates/lot.php', ['good' => $good]); // сохраняем в переменную разметку страницы товара
        }

        // -------------------------------------

    } else {
        $page_content = render('templates/add-lot.php'); // сохраняем разметку добавления лота в переменную
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'good_categories' => $good_categories]);
