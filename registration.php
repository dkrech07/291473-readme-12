<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

$is_auth = 1;
$user_name = 'Дмитрий';

$fields_map = [
    'email' => 'Email. ',
    'login' => 'Логин. ',
    'password' => 'Пароль. ',
    'password-repeat' => 'Повтор пароля. ',
];

$errors = check_registration_validity($con, $fields_map);

$page_content = include_template('registration.php', ['errors' => $errors,]);

$layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'readme: регистрация',
    'content' => $page_content,
]);

echo($layout_content);
