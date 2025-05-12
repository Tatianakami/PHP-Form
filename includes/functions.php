<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function set_message($msg, $type = 'success') {
    $_SESSION['message'] = ['text' => $msg, 'type' => $type];
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function simple_validate_date($date) {
    return (bool)strtotime($date);
}