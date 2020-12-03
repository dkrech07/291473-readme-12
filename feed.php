<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

// Получает список подписок авторизованного пользователя;
$subscriber_id = $_SESSION['user']['id'];
$subscriptions_query = select_query($con, "SELECT author_id FROM subscriptions WHERE subscriber_id = '$subscriber_id'");

$subscriptions_ids = array();
foreach ($subscriptions_query as $subscription_number => $subscription_id) {
    $subscriptions_ids[] = $subscription_id['author_id'];
}

// Реализует фильтрацию постов в ленте;
$content_types = select_query($con, 'SELECT * FROM content_types');
// Проверяет наличие параметра запроса: если параметр есть, фильтрует по нему данные из БД;
$post_type = filter_input(INPUT_GET, 'post-type', FILTER_VALIDATE_INT);
if ($post_type) {
    $post_type_query = 'AND p.content_type_id = ' . $post_type;
} else {
    $post_type = null;
    $post_type_query = null;
}

// Получать список постов пользователей на которых оформлена подписка;
$subscription_ids_list = "'" . implode("', '", $subscriptions_ids) . "'";
$subscription_posts = select_query($con, "SELECT p.*, u.login, u.date_add, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE (post_author_id) IN ($subscription_ids_list) " . $post_type_query);

$page_content = include_template('feed.php', [
    'subscription_posts' => $subscription_posts,
    'content_types' => $content_types,
    'post_type' => $post_type,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: моя лента',
  'content' => $page_content,
]);

echo($layout_content);
