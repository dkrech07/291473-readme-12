<?php
require_once('helpers.php');
require_once('functions.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$page_content = include_template('post.php', []);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

echo($layout_content);
