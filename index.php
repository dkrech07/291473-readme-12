<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');
require_once('includes/functions.inc.php');
$con = mysqli_connect('localhost', 'root', 'root', 'readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);

$fields_map = [
    'login' => 'Логин. ',
    'password' => 'Пароль. ',
];

$errors = checkAutorization($con, $fields_map);

$layout_content = include_template('layout-unauth.php', ['errors' => $errors]);
echo($layout_content);
