<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');
require_once('functions.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$con = mysqli_connect('localhost', 'root', 'root','readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);
$content_types = select_query($con, 'SELECT * FROM content_types');

if (isset($_GET['post_type'])) {
  $post_type = filter_input(INPUT_GET, 'post_type');
  $post_type_query = 'WHERE p.content_type_id = ' . $post_type;
} else {
  $post_type = null;
  $post_type_query = '';
}

$posts = select_query($con, 'SELECT p.*, u.login, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ORDER BY p.views DESC');

$current_sorting_type = filter_input(INPUT_GET, 'sorting-type');

$page_content = include_template('main.php', [
    'posts' => $posts,
    'content_types' => $content_types,
    'post_type' => $post_type,
    'current_sorting_type' => $current_sorting_type,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: популярное',
  'content' => $page_content,
]);

echo($layout_content);
