<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$search_posts = [];
$search = filter_input(INPUT_GET, 'q') ?? '';
if (!empty($search)) {

  if ($search[0] == '#') {
    $search_line = trim(substr($search, 1));
    $search_query = "SELECT p.*, u.login, u.date_add, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id 
    INNER JOIN content_types ct ON ct.id = p.content_type_id INNER JOIN post_hashtags ph ON ph.post_id = p.id INNER JOIN hashtags h ON h.id = ph.hashtag_id 
    WHERE MATCH(hashtag_name) AGAINST(?)";
  } else {
    $search_line = trim($search);
    $search_query = "SELECT p.*, u.login, u.date_add, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id 
    INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE MATCH(title, content) AGAINST(?)";
  }

  $stmt = mysqli_prepare($con, $search_query);
  mysqli_stmt_bind_param($stmt, 's', $search);
  mysqli_stmt_execute($stmt);
  $search_result = mysqli_stmt_get_result($stmt);
  $search_posts = mysqli_fetch_all($search_result, MYSQLI_ASSOC);
  $search_tags = '#' . str_replace(' ', " #", $search_line);
}

$page_content = include_template('search.php', [
  'search_tags' => $search_tags,
  'search_posts' => $search_posts,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: страница результатов поиска',
  'content' => $page_content,
]);

echo($layout_content);