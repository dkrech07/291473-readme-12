<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
$user = select_query($con, "SELECT * FROM users WHERE id = ". $user_id, 'assoc');
$user_posts = select_query($con, "SELECT p.*, ct.type_name, ct.class_name FROM posts p INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE p.post_author_id = ". $user_id);
$user_posts_count = select_query($con, "SELECT COUNT(*) FROM posts WHERE post_author_id = " . $user['id'], 'row');
$user_subscribers_count = select_query($con, "SELECT COUNT(*) FROM subscriptions WHERE author_id = " . $user['id'], 'row');

// Получает id постов пользователя;
$user_posts_ids = array();
foreach($user_posts as $user_post) {
  $user_posts_ids[] = $user_post['id'];
}
// Записывает список id постов пользователя в строку;
$user_posts_ids_list = implode(", ", $user_posts_ids);
// Запрашивает хештеги для списка постов;
$posts_hashtags = select_query($con, "SELECT ph.post_id, h.hashtag_name FROM post_hashtags ph INNER JOIN hashtags h ON h.id = ph.hashtag_id WHERE (post_id) IN ($user_posts_ids_list)");

$subscribe_user_id = filter_input(INPUT_GET, 'subscribe_user', FILTER_VALIDATE_INT);

if (isset($subscribe_user_id)) {
  header('Location: /profile.php?user=' . $subscribe_user_id);

}

// print($user['id']);


// print_r($subscribe_user_id);

// $hashtags_ids = select_query($con, 'SELECT hashtag_id FROM post_hashtags WHERE post_id = ' . $user_posts['id']);
// $post_hashtags = array();
// print_r($posts_hashtags);
// $post_hashtags_line = get_hashtag_name($con, $hashtags_id);
// foreach ($post_hashtags_line as $post_hashtag_number => $post_hashtag) {
//     $post_hashtags[$post_hashtag_number] = $post_hashtag;
// }
//$user_info = select_query($con, "SELECT u.*, p.* FROM users u INNER JOIN posts p ON p.post_author_id = u.id WHERE u.id = ". $user_id, assoc);
//$user_posts_count = select_query($con, "SELECT COUNT(*) FROM posts WHERE post_author_id = ". $user_id, row);
//$user_subscribers_count = select_query($con, "SELECT COUNT(*) FROM subscriptions WHERE author_id = " . $user_id, row);
// print_r($hashtags_id);
//$post_content = include_template('post-' . $post['class_name'] .'.php', ['post' => $post, 'registration_time' => $registration_time,]);

if (!empty($_POST['subscribe'])) {
  print($_POST['subscribe']);

}


$page_content = include_template('profile.php', [
  'user' => $user,
  'user_posts_count' => $user_posts,
  'user_posts' => $user_posts,
  'post_hashtags' => $posts_hashtags,
  'user_posts_count' => $user_posts_count,
  'user_subscribers_count' => $user_subscribers_count,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

echo($layout_content);