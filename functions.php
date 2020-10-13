<?php

function format_price($num) {
    $result = $num;
    ceil($result);
    if ($result >= 1000) {
        $result = number_format($result, 0, '.', ' ');
    }
    $result = $result . ' ₽';
    return $result;
};

function render($path, $vars = array()) {
    if(file_exists($path)) {
        ob_start();
        extract($vars);
        require $path;
        return ob_get_clean();
    } else {
        return 'Невозможно загрузить ' . $path;
    }
};

function time_left($termination_ts, $utc) {
    $termination_ts = strtotime('tomorrow');
    $now_ts = strtotime('now');
    $time_shift = 3600 * $utc;
    $left_ts = floor($termination_ts - $now_ts - $time_shift);
    return date('G:i', $left_ts);
}

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) . ',' . json_encode(gettype($data)) . ')';
    echo '</script>';
}

function search_user_by_email($email, $users) {
    $result = null;
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $result = $user;
            break;
        }
    }
    return $result;
}

function get_array_from_db ($link, $sql) {
    // Получаем категории товаров
    $result = mysqli_query($link, $sql);
    // ===== Проверка SQL запроса
    if ($result) { // Получен ответ
        return mysqli_fetch_all($result, MYSQLI_ASSOC); // Преобразуем полученные данные в массив
    } else { // Получена ошибка
        console_log(mysqli_error($link));
        return mysqli_error($link);
    }
}

function get_time_left ($deadline) {
    $now = date('Y-m-d H:i:s', strtotime('now')); // Получаем строчное значение текущей даты
    // Получаем строчное значение даты конца торгов из массива товара

    $dte_now = new DateTime($now); // Переводим обе даты в формат DateTime
    $dte_deadline = new DateTime($deadline);

    $dte_diff  = $dte_now->diff($dte_deadline); // Счтаем разницу между датами
    if ($dte_now > $dte_deadline) {
        return 'Торги окончены';
    } else {
        return $dte_diff->format("<strong>%d дн.</strong> <br> %H:%I:%S");
    }
}

function add_timer_class ($deadline) {
    $now = date('Y-m-d H:i:s', strtotime('now')); // Получаем строчное значение текущей даты
    // Получаем строчное значение даты конца торгов из массива товара

    $dte_now = new DateTime($now); // Переводим обе даты в формат DateTime
    $dte_deadline = new DateTime($deadline);

    $dte_diff  = $dte_now->diff($dte_deadline); // Счтаем разницу между датами
    if ($dte_now > $dte_deadline) {
        return 'timer--end';
    } elseif ($dte_diff->d <= 0 && $dte_diff->s > 1) {
        return 'timer--finishing';
    } else {
        return '';
    }
}

function format_date ($date_str) {
    $now = date('Y-m-d H:i:s', strtotime('now'));
    $bid_date = ($date_str);
    $dte_now = new DateTime($now);
    $dte_bid = new DateTime($bid_date);

    $dte_diff = $dte_now->diff($dte_bid);

    if ($dte_diff->d < 2 && $dte_diff->d >= 1)  {
        $time = $dte_bid->format('H:i');
        $result = "Вчера в $time";
    } elseif ($dte_diff->d < 1 && $dte_diff->h >= 1 ) {
        $result = "$dte_diff->h час. назад";
    } elseif ($dte_diff->h < 1 && $dte_diff->i >= 1) {
        $result = "$dte_diff->i мин. назад";
    } elseif ($dte_diff->i < 1 && $dte_diff->s >= 0) {
        $result = "менее мин. назад";
    } else {
        $result = date('d.m.y в H:i', strtotime($bid_date));
    }
    return $result;
}
