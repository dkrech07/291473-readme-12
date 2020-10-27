<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');
require_once('includes/functions.inc.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

// Получает из параметра запроса тип сортировки; Этот пункт пока что что на паузе;
$sorting_type = filter_input(INPUT_GET, 'sorting-type');
// Подключается к БД;
$con = mysqli_connect('localhost', 'root', 'root','readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);
// Получает спиок типов контента для дальнейшего вывода на странице;
$content_types = select_query($con, 'SELECT * FROM content_types');
// Проверяет наличие параметра запроса: если параметр есть, фильтрует по нему данные из БД;
$post_type = intval(filter_input(INPUT_GET, 'post-type'));
if ($post_type) {
  $post_type_query = 'WHERE p.content_type_id = ' . $post_type;
} else {
  $post_type = null;
  $post_type_query = null;
}
// Получает список постов (в зависимости от выбранного типа контента);
$posts = select_query($con, 'SELECT p.*, u.login, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ORDER BY p.views DESC');
if (!$posts) {
  open_404_page();
}

// Передает данные из БД в шаблоны;
$page_content = include_template('main.php', [
    'posts' => $posts,
    'content_types' => $content_types,
    'post_type' => $post_type,
    'sorting_type' => $sorting_type,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: популярное',
  'content' => $page_content,
]);

echo($layout_content);
