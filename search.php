<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

$search_posts = [];
mysqli_query($con, "CREATE FULLTEXT INDEX posts_search ON posts(title, content)");
$search = filter_input(INPUT_GET, 'q') ?? '';
print($search);

if ($search) {
  $search_query = "SELECT * FROM posts WHERE MATCH(title, content) AGAINGS(?)";
  $stmt = mysqli_prepare($con, $search_query);
  mysqli_stmt_bind_param($stmt, 's', $search);
  mysqli_stmt_execute($stmt);
  $search_result = mysqli_stmt_get_result($stmt);
  $search_posts = mysqli_fetch_all($search_result, MYSQL_ASSOC);
}

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