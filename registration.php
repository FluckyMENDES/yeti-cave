<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/users.php';

$page_title = 'Регистрация нового пользователя';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Если зашли с на страницу с отправленной формой

    $form = $_POST; // Создадим переменную для удобства

    $required_fields = ['email', 'password', 'name', 'address']; // Поля которые мы проверяем
    $errors = []; // Массив для будущих ошибок

    // ----- Валидация пустых полей -----
    foreach ($required_fields as $field) { // Запускаем цикл для каждого проверяемого поля
        if (empty($form[$field])) { // Если поле пустое
            $errors[$field] = 'Необходимо заполнить данное поле'; // Добавляем в массив ошибок сообщение с данным полем
        }
    }
    // Если все поля прошли проверку на заполненность
    if (!count($errors)) { // Если в массиве ошибок нет ошибок

        // ----- Валидация E-Mail -----
        if ($user = search_user_by_email($form['email'], $users)) { // Если в базе данных уже имеется такой e-mail
            $errors['email'] = 'Пользователь с указанной почтой уже зарегистрирован'; // Добавляем соотв. ошибку
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) { // Если введенные данные не корректный email
            $errors['email'] = 'Введите корректный e-mail'; // Добавляем соотв. ошибку
        }

        // ----- Валидация файла -----
        if (isset($_FILES['avatar']) && $_FILES['avatar']['name']) { // Если в массиве $_FILES есть файл с именем 'avatar' и у него есть есть свойство 'name'
            $tmp_name = $_FILES['avatar']['tmp_name']; // Записываем в свойство 'tmp_name' файла временное имя
            $path = 'img/upload/' . $_FILES['avatar']['name']; // Создаем полную ссылку на файл

            $file_size = $_FILES['avatar']['size']; // Помещаем свойство размера файла в переменную
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // Получаем MIMI-тип файла в переменную
            $file_type = finfo_file($finfo, $tmp_name); // Получаем тип-файла

            if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') { // Если тип файла отличный от 'jpeg' или 'png'
                $errors['avatar'] = 'Загрузите картинку в формате JPEG или PNG'; // Добавляем соотв. ошибку
            }

            if ($file_size > 100000) {
                $errors['avatar'] = 'Максимальный размер файла 100кб';
            }

            if (!count($errors)) { // Если нет никаких ошибок
                move_uploaded_file($tmp_name, $path); // перемещаем загруженный файл в директорию загрузок
                $user['avatar'] = $path; // добавляем в массив $user значение по ключу avatar
            }
        }
        // ----- Валидация пароля -----
        if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=\S+$).{8,}$/", $form['password'])) { // Пароль не прошел проверку регулярным выражением
            $errors['password'] = 'Как минимум одна цифра, заглавная и строчная буквы и символ. Запрещены кирилические символы и пробелы. Длинна пароля не менее 8 символов.'; // Добавляем в массив ошибок соотв. ошибку
        }
        // ----- Валидация имени -----
        if (iconv_strlen($form['name']) > 20) {
            $errors['name'] = 'Имя должно содержать не более 20 символов';
        }
    }

    if (count($errors)) { // Если в массиве с ошибками есть ошибки
        $page_content = render ('templates/registration.php', ['form' => $form, 'errors' => $errors]); // Выводим шаблон страницы входа и передаем в него массив
    // ########## УСПЕШНАЯ РЕГИСТРАЦИЯ ##########
    } else { // Если ошибок нет
        foreach ($form as $key => $value) { // Проходимся по всем полям
            if ($key === 'password') { // Если поле 'password'
                $user[$key] = password_hash($value, PASSWORD_DEFAULT); // Записываем в массив пользователя его хеш
            } else { // Если все остальные поля
                $user[$key] = $value; // Записываем их в массив пользователя
            }
        }
        if (isset($_FILES['avatar'])) { // Если была загружена аватарка
            $user['avatar'] = $path; // Добавляем путь к ней в массив пользователя
        }
        $user['reg_date'] = date('Y-m-d H:i:s'); // Добавляем в массив пользователя дату регистрации
        // Обработка данных для запроса в БД
        $keys = implode(", ",array_keys($user)); // Получаем все ключи массива $user и приводим их к строке с разделителем ", "
        $values = implode("', '",array_values($user));  // Получаем все значения массива $user и приводим их к строке с разделителем ", "
        $sql = "INSERT INTO users ($keys) VALUES ('$values');"; // Формируем SQL запрос
        $result = mysqli_query($link, $sql); // Посылаем запрос на запись данных пользователя в БД

        // Производим вход на сайт
        $_SESSION['user'] = $user; // Приваиваем в сессию массив с данными этого юзера
        header('Location: /index.php'); // Перенаправляем пользователя на шлавную страницу
        exit(); // Прекращаем дальнейшее выполнение скрипта
    }
} else { // Если зашли на страницу без POST запроса
    if (isset($_SESSION['user'])) { // Если в массиве сессии имеется ключ user, т.е. пользователь вошел на сайт
        require_once 'db/goods.php'; // Запрашиваем данные товаров
        $page_content = render('templates/index.php', ['username' => $_SESSION['user']['name'], 'goods' => $goods]); // Выводим шаблон главной страницы и передаем в него имя пользователя
    } else { // Если в массиве сессии нет ключа user, т.е. пользователь не вошел на сайт
        $page_content = render ('templates/registration.php', []); // Выводим шаблон страницы регистрации.
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
