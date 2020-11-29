<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
print_r($_SESSION);
$is_auth = isset($_SESSION);
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

// Получает список подписок авторизованного пользователя;
$subscriber_id = $_SESSION['user']['id'];
$subscriptions_query = select_query($con, "SELECT author_id FROM subscriptions WHERE subscriber_id = '$subscriber_id'");
print_r($subscriptions_query);

$page_content = include_template('feed.php', []);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: моя лента',
  'content' => $page_content,
]);

echo($layout_content);
