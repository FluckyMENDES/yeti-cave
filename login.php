<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
require_once 'init.php';
require_once 'db/users.php';

$page_title = 'Вход в аккаунт';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Если зашли с на страницу с отправленной формой
    $form = $_POST; // Создадим переменную для удобства

    $required_fields = ['email', 'password']; // Поля которые мы проверяем
    $errors = []; // Массив для будущих ошибок

    foreach ($required_fields as $field) { // Запускаем цикл для каждого проверяемого поля
        if (empty($form[$field])) { // Если поле пустое
            $errors[$field] = 'Необходимо заполнить данное поле'; // Добавляем в массив ошибок сообщение с данным полем
        }
    }
    // Если все поля прошли проверку на заполненность
    if (!count($errors)) { // Если в массиве ошибок нет ошибок

        if ($user = search_user_by_email($form['email'], $users)) { // Если введенный и-мейл содежится в базе данных
            // Обработка данных для запроса к БД
            $user_email = implode($user); // Приводим к строке массив с единственным ключом 'email'
            $sql = "SELECT password FROM users WHERE email='$user_email';"; // Запрос на получение хеша-пароля с данным 'email'
            $user_hash = implode(mysqli_fetch_assoc(mysqli_query($link, $sql))); // Отправляем запрос в БД, полученные данные приводим к строке
        } else { // E-mail не найдем в БД
            $errors['email'] = 'Пользователь с указанной почтой не зарегистрирован'; // Добавляем соотв. ошибку
        }

        if (!password_verify($form['password'], $user_hash)) { // Если хэш-пароля введенного в форму и хэш-пароля в базе данных не сходятся
            $errors['password'] = 'Неверный пароль'; // Добавляем в массив ошибок соотв. ошибку
        }

    } else { // Если в массиве есть ошибки
        $errors['email'] = 'Необходимо заполнить данное поле'; // Добавляем в массив ошибок соотв. ошибку
    }

    if (count($errors)) { // Если в массиве с ошибками есть ошибки
        $page_content = render ('templates/login.php', ['form' => $form, 'errors' => $errors]); // Выводим шаблон страницы входа и передаем в него массив с ошибками
    // ####################### УСПЕШНЫЙ ВХОД ##########################
    } else { // Если ошибок нет
        // Обработка данных для запроса в БД
        $user_email = $form['email']; // Сохраняем в переменную e-mail пользователя
        $sql = "SELECT * FROM users WHERE email='$user_email';"; // Запрос в БД для получаения всех полей данного пользователя
        $user = mysqli_fetch_assoc(mysqli_query($link, $sql)); // Отправляем запрос
        $_SESSION['user'] = $user; // Приваиваем в сессию массив с данными этого юзера

        header('Location: /index.php'); // Перенаправляем пользователя на шлавную страницу
        exit(); // Прекращаем дальнейшее выполнение скрипта
    }

} else { // Если зашли на страницу без POST запроса

    if (isset($_SESSION['user'])) { // Если в массиве сессии имеется ключ user, т.е. пользователь уже вошел на сайт
        require_once 'db/goods.php'; // Получаем данные карточек товара
        $page_content = render('templates/index.php', ['username' => $_SESSION['user']['name'], 'goods' => $goods]); // Выводим шаблон главной страницы и передаем в него имя пользователя
    } else { // Если в массиве сессии нет ключа user, т.е. пользователь не вошел на сайт
        $page_content = render ('templates/login.php', []); // Выводим шаблон страницы входа.
    }
}

// Выводим разметку лейаута, передаем туда разметку страницы товара и необходимые переменные;
echo render('templates/layout.php', ['page_content' => $page_content, 'page_title' => $page_title, 'categories' => $categories]);
