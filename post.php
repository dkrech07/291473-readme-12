<?php
require_once('helpers.php');
require_once('functions.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$current_post_id = filter_input(INPUT_GET, 'id');
$con = mysqli_connect('localhost', 'root', 'root','readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);
$post = select_query($con, 'SELECT p.*, u.login, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE p.id = ' . $current_post_id);

$post2 = select_query($con, 'SELECT * FROM posts WHERE id = ' . $current_post_id);

print_r($post2);
print_r($post[0]['class_name']);

$post_content = include_template('post-' . $post[0]['class_name'] .'.php', ['post' => $post[0]]);

$page_content = include_template('post.php', ['post_content' => $post_content]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

echo($layout_content);
