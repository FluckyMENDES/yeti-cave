<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'userdata.php';

$page_title = 'Регистрация нового пользователя';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Если зашли с на страницу с отправленной формой
//    console_log('Зашли на страницу с отправленной формой');
//    console_log($_FILES);
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
//        console_log('Поля не пустые');
        // ----- Валидация E-Mail -----
        if ($user = search_user_by_email($form['email'], $users)) { // Если в базе данных имеется такой e-mail
//            console_log('Пользователь с таким e-mail уже зарегистрирован');
//            console_log($user);
            $errors['email'] = 'Пользователь с указанной почтой уже зарегистрирован';
        } elseif (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) { // Если данный email не заренистрирован
            $errors['email'] = 'Введите корректный e-mail';
        }
        // ----- Валидация файла -----

        if (isset($_FILES['avatar']) && $_FILES['avatar']['name']) {
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $path = 'img/upload/' . $_FILES['avatar']['name'];

            $file_size = $_FILES['avatar']['size'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);

            if ($file_type !== 'image/jpeg') {
                $errors['avatar'] = 'Загрузите картинку в формате JPEG, PNG';
            } else {
                move_uploaded_file($tmp_name, $path); // перемещаем загруженный файл в директорию
                $good['avatar'] = $path; // добавляем в массив $_POST значение по ключу image_url
            }

            if ($file_size > 100000) {
                $errors['avatar'] = 'Максимальный размер файла 100кб';
            }
        }
        // ----- Валидация пароля -----
        if (preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!])(?=\S+$).{8,}$/", $form['password'])) { // Пароль прошел проверку регулярным выражением
//            console_log('Пароль прошел проверку');
        } else { // Пароль не прошел проверку регулярным выражением
//            console_log('Пароль должен содежрать как минимум цифру, заглавную и строчную букву. Запрещены кирилические символы.');
            $errors['password'] = 'Пароль должен содежрать как минимум цифру, заглавную и строчную букву и символ. Запрещены кирилические символы и пробелы. Длинна пароля должна быть не менее 8 символов.'; // Добавляем в массив ошибок соотв. ошибку
        }
    }

    if (count($errors)) { // Если в массиве с ошибками есть ошибки
//        console_log('Есть ошибки');
        $page_content = render ('templates/registration.php', ['form' => $form, 'errors' => $errors]); // Выводим шаблон страницы входа и передаем в него массив
    } else { // Если ошибок нет
//        console_log('Нет ошибок');
        // Создаем массив $user
        foreach ($form as $key => $value) {
            $user[$key] = $value;
        }
        if (isset($_FILES['avatar'])) {
            $user['avatar'] = $path;
        }
        // Производим вход на сайт
        $_SESSION['user'] = $user; // Приваиваем в сессию массив с данными этого юзера
        header('Location: /index.php'); // Перенаправляем пользователя на шлавную страницу
        exit(); // Прекращаем дальнейшее выполнение скрипта
    }
} else { // Если зашли на страницу без POST запроса
//    console_log('Зашли на страницу не отправляя форму входа');
    if (isset($_SESSION['user'])) { // Если в массиве сессии имеется ключ user
//        console_log('Уже был произведен вход');
        $page_content = render('templates/index.php', ['username' => $_SESSION['user']['name'], 'goods' => $goods]); // Выводим шаблон главной страницы и передаем в него имя пользователя
    } else { // Если в массиве сессии нет ключа user, т.е. пользователь не вошел на сайт
//        console_log('Первое попадание на страницу входа');
        $page_content = render ('templates/registration.php', []); // Выводим шаблон страницы регистрации.
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'good_categories' => $good_categories]);
