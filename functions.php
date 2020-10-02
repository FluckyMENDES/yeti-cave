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
