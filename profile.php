<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

//$posts = select_query($con, 'SELECT p.*, u.login, u.*, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ' . $sorting_order);

$user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
$user = select_query($con, "SELECT * FROM users WHERE id = ". $user_id, assoc);
$user_posts = select_query($con, "SELECT * FROM posts WHERE post_author_id = ". $user_id);

//$user_info = select_query($con, "SELECT u.*, p.* FROM users u INNER JOIN posts p ON p.post_author_id = u.id WHERE u.id = ". $user_id, assoc);
//$user_posts_count = select_query($con, "SELECT COUNT(*) FROM posts WHERE post_author_id = ". $user_id, row);
//$user_subscribers_count = select_query($con, "SELECT COUNT(*) FROM subscriptions WHERE author_id = " . $user_id, row);

print_r($user_posts);

$page_content = include_template('profile.php', [
  'user' => $user,
  'user_posts_count' => $user_posts,
  'user_subscribers_count' => $$user_subscribers_count,
  'user_info' => $user_info,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

echo($layout_content);