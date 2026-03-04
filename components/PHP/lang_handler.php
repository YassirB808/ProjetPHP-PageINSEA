<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$allowed_langs = ['fr', 'en', 'ar'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed_langs)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'fr';

$translations = include __DIR__ . '/../languages/' . $current_lang . '.php';

function __($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

function get_dir() {
    global $translations;
    return isset($translations['dir']) ? $translations['dir'] : 'ltr';
}

function get_lang_code() {
    global $current_lang;
    return $current_lang;
}
?>
