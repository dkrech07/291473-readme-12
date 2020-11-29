<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$is_auth = isset($_SESSION);
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

// Получает список подписок авторизованного пользователя;
$subscriber_id = $_SESSION['user']['id'];
$subscriptions_query = select_query($con, "SELECT author_id FROM subscriptions WHERE subscriber_id = '$subscriber_id'");
foreach ($subscriptions_query as $subscription_number => $subscription_id) {
    $subscriptions_ids[] = $subscription_id['author_id'];
}

// Получать список постов пользователей на которых оформлена подписка;
$subscription_ids_list = implode(" AND ", $subscriptions_ids);
$subscription_posts = select_query($con, 'SELECT p.*, u.login, u.date_add, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE p.post_author_id = ' . $subscription_ids_list);

$page_content = include_template('feed.php', ['subscription_posts' => $subscription_posts]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: моя лента',
  'content' => $page_content,
]);

echo($layout_content);
