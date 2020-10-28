<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

// Получает ID поста из параметра запроса;
$current_post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
// Проверяет наличие параметра запроса;
if (!$current_post_id) {
  open_404_page();
}
// Подключается к БД;
$con = mysqli_connect('localhost', 'root', 'root','readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);
// Плучает пост за БД по ID запроса;
$post = select_query($con, 'SELECT p.*, u.login, u.date_add, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id WHERE p.id = ' . $current_post_id, 'assoc');
// Проверяет наличие запрошенных данных в ответе от БД;
if (!$post) {
  open_404_page();
}
// Получает время регистрации пользователя;
$registration_time = get_post_interval($post['date_add'], 'на сайте');
// Получает id автора поста;
$post_author_id = select_query($con, 'SELECT post_author_id FROM posts WHERE posts.id = ' . $current_post_id, 'row');
// Получает общее количества постов автора открытого поста;
$author_posts_count = select_query($con, 'SELECT COUNT(*) FROM posts WHERE post_author_id = ' . $post_author_id , 'row');
// Получает общее количество подписчиков автора открытого поста;
$subscribers_count = select_query($con, 'SELECT COUNT(*) FROM subscriptions WHERE author_id = ' . $post_author_id, 'row');
// Передает данные из БД в шаблоны;
$post_content = include_template('post-' . $post['class_name'] .'.php', ['post' => $post, 'registration_time' => $registration_time,]);

$page_content = include_template('post.php', [
    'post' => $post,
    'post_content' => $post_content,
    'registration_time' => $registration_time,
    'author_posts_count' => $author_posts_count,
    'subscribers_count' => $subscribers_count,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: публикация',
  'content' => $page_content,
]);

/*6. В post.php не нужно делать
```echo($layout_content);```
лучше сделать функцию внутри, которая возвращает `$layout_content` и делать echo вызов_функции(); иначе потом будет трудно собирать все echo по куче файлов. То есть в файлах с расширением .inc.php лучше ничего не выводить в браузер напрямую.*/
function get_layout_content($layout_content) {
    return $layout_content;
}

echo(get_layout_content($layout_content));
