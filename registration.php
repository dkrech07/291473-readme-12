<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

$fields_map = [
    'email' => 'Email. ',
    'login' => 'Логин. ',
    'password' => 'Пароль. ',
    'password-repeat' => 'Повтор пароля. ',
];

$errors = check_registration_validity($con, $fields_map);

$page_content = include_template('registration.php', ['errors' => $errors,]);

$layout_content = include_template('layout.php', [
    'title' => 'readme: регистрация',
    'content' => $page_content,
]);

echo($layout_content);
