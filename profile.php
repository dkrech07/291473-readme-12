<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$user_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
if (empty($user_id)) {
  $user_id = $_SESSION['user']['id'];
}
$user = select_query($con, "SELECT * FROM users WHERE id = ". $user_id, 'assoc');
$user_posts = select_query($con, "SELECT p.*, ct.type_name, ct.class_name FROM posts p INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE p.post_author_id = ". $user_id);
$user_posts_count = select_query($con, "SELECT COUNT(*) FROM posts WHERE post_author_id = " . $user['id'], 'row');
$user_subscribers_count = select_query($con, "SELECT COUNT(*) FROM subscriptions WHERE author_id = " . $user['id'], 'row');

// Получает id постов пользователя;
$user_posts_ids = array();
foreach($user_posts as $user_post) {
  $user_posts_ids[] = $user_post['id'];
}

if (!empty($user_posts_ids)) {
// Записывает список id постов пользователя в строку;
$user_posts_ids_list = implode(", ", $user_posts_ids);
// Запрашивает хештеги для списка постов;
$posts_hashtags = select_query($con, "SELECT ph.post_id, h.hashtag_name FROM post_hashtags ph INNER JOIN hashtags h ON h.id = ph.hashtag_id WHERE (post_id) IN ($user_posts_ids_list)");
}

$subscribe_user_id = filter_input(INPUT_GET, 'subscribe_user', FILTER_VALIDATE_INT);

if (isset($subscribe_user_id)) {
  header('Location: /profile.php?user=' . $subscribe_user_id);
  exit();
}

$user_id = $_SESSION['user']['id'];
$subscribe_user_id = $user['id'];
$check_subscribe = select_query($con, "SELECT * FROM subscriptions WHERE subscriber_id = '$user_id' AND author_id = '$subscribe_user_id'");

if (!empty($check_subscribe)) {
  $subscribe = $user_id;
} else {
  $subscribe = false;
}

$get_subscribe = filter_input(INPUT_GET, 'subscribe');
$get_unsubscribe = filter_input(INPUT_GET, 'unsubscribe');

// поиск пользователя на которого подписываемся
$subscribe_user = select_query($con, "SELECT * FROM users WHERE (id) IN ($subscribe_user_id)");

if (!empty($get_subscribe)) {
  $subscribe_user = select_query($con, "SELECT * FROM users WHERE (id) IN ($subscribe_user_id)");
  if (isset($subscribe_user)) {
    mysqli_query($con, "INSERT INTO subscriptions (subscriber_id, author_id) VALUES ('$user_id', '$subscribe_user_id')");

    $email = $subscribe_user[0]['email'];
    $login = $subscribe_user[0]['login'];
    $email_message = 'Здравствуйте, ' . $subscribe_login . '. На вас подписался новый пользователь ' . $user_name . '. Вот ссылка на его профиль: http://readme/profile.php?user=' . $user_id;
    require_once('includes/smtp.php');
  }
  header("Location: /profile.php?user=$subscribe_user_id");
  exit();
}

if (!empty($get_unsubscribe)) {
  $subscribe_user = select_query($con, "SELECT * FROM users WHERE (id) IN ($subscribe_user_id)");
  if (isset($subscribe_user)) {
    mysqli_query($con, "DELETE FROM subscriptions WHERE subscriber_id = '$user_id' AND author_id = '$subscribe_user_id'");
  }
  header("Location: /profile.php?user=$subscribe_user_id");
  exit();
}

$page_content = include_template('profile.php', [
  'user' => $user,
  'user_posts_count' => $user_posts,
  'user_posts' => $user_posts,
  'post_hashtags' => $posts_hashtags,
  'user_posts_count' => $user_posts_count,
  'user_subscribers_count' => $user_subscribers_count,
  'subscribe' => $subscribe,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

echo($layout_content);