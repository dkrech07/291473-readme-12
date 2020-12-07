<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$search = filter_input(INPUT_GET, 'q');

$search_query = "SELECT * FROM posts WHERE MATCH(title, content) AGAINGS ('любим')";
$search_posts = select_query($con, $search_query);

$page_content = include_template('search.php', [
  'search_posts' => $search_posts,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: страница результатов поиска',
  'content' => $page_content,
]);

echo($layout_content);