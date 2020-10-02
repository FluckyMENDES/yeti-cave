<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'data.php';
require_once 'userdata.php';

$page_title = 'Вход в аккаунт';

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Если зашли с на страницу с отправленной формой
//    console_log('Зашли на страницу с отправленной формой');
    $form = $_POST; // Создадим переменную для удобства

    $required_fields = ['email', 'password']; // Поля которые мы проверяем
    $errors = []; // Массив для будущих ошибок
    foreach ($required_fields as $field) { // Запускаем цикл для каждого проверяемого поля
        if (empty($form[$field])) { // Если поле пустое
            $errors[$field] = 'Необходимо заполнить данное поле'; // Добавляем в массив ошибок сообщение с данным полем
        }
    }
    // Если все поля прошли проверку на заполненность
    if (!count($errors)) { // Если в массиве ошибок нет ошибок и введенный и-мейл содежится в массиве данных $users - сохраняем в переменную $user пользователя с указанным и-мейлом
//        console_log('Поля не пустые');
        if ($user = search_user_by_email($form['email'], $users)) {
//            console_log('Указанный и-мейл есть в базе');
        } else {
//            console_log('Укзанного и-мейла нет в базе');
            $errors['email'] = 'Пользователь с указанной почтой не зарегистрирован';
        }
        if (password_verify($form['password'], $user['password'])) { // и если хэш-пароля введенного в форму и хэш-пароля в базе данных сходятся
//            console_log('Пароли совпадают');
            $_SESSION['user'] = $user; // Приваиваем в сессию массив с данными этого юзера
        } else { // Если хэши паролей не совпадают
//            console_log('Пароли не совпадают');
            $errors['password'] = 'Неверный пароль'; // Добавляем в массив ошибок соотв. ошибку
        }
    } else { // Если в массиве есть ошибки и указаного и-мейла нет в базе данных
//        console_log('Есть ошибки');
//        $errors['email'] = 'Такой пользователь не найден'; // Добавляем в массив ошибок соотв. ошибку
    }

    if (count($errors)) { // Если в массиве с ошибками есть ошибки
//        console_log('Есть ошибки');
        $page_content = render ('templates/login.php', ['form' => $form, 'errors' => $errors]); // Выводим шаблон страницы входа и передаем в него массив
    } else { // Если ошибок нет
//        console_log('Нет ошибок');
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
        $page_content = render ('templates/login.php', []); // Выводим шаблон страницы входа.
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'good_categories' => $good_categories]);
