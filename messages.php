<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$chat_user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
print($chat_user_id);

// Передает данные из БД в шаблоны;
$page_content = include_template('messages.php', [
  'avatar' => $avatar,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: личные сообщения',
  'content' => $page_content,
]);

echo($layout_content);
