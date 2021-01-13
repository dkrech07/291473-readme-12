<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];

// Получает спиок типов контента для дальнейшего вывода на странице;
$content_types = select_query($con, 'SELECT * FROM content_types');
// Проверяет наличие параметра запроса: если параметр есть, фильтрует по нему данные из БД;
$post_type = filter_input(INPUT_GET, 'post-type', FILTER_VALIDATE_INT);
if ($post_type) {
    $post_type_query = 'WHERE p.content_type_id = ' . $post_type;
} else {
    $post_type = null;
    $post_type_query = null;
}

// Получает из параметра запроса тип сортировки;
$sorting_type = filter_input(INPUT_GET, 'sorting-type');
$sorting_direction = filter_input(INPUT_GET, 'sorting-direction');

if (!$sorting_direction) {
    $sorting_type = 'popular';
    $sorting_direction = 'desc';
}

if ($sorting_type == 'popular') {
    $sorting_order = 'ORDER BY p.views ' . $sorting_direction;
} elseif ($sorting_type == 'likes') {
    $sorting_order = 'ORDER BY p.likes_count ' . $sorting_direction;
} elseif ($sorting_type == 'date') {
    $sorting_order = 'ORDER BY p.date_add ' . $sorting_direction;
} else {
    $sorting_order = 'ORDER BY p.views ' . $sorting_direction;
}

// Получает список постов (в зависимости от выбранного типа контента);
//$posts = select_query($con, 'SELECT p.*, u.login, u.*, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ' . $sorting_order);

$page = 0;
$current_page = 1;
$next_page = 0;
$limit = 9;

// Вычислить количество постов;
// Вычислить количество страниц;
$posts_count = select_query($con, "SELECT COUNT(*) FROM posts", 'row');

if ($posts_count > 9) {
    $pages_count = ceil($posts_count/$limit);
    if ($posts_count%$limit !== 0) {
        $pages_count++;
    }

    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if (isset($page)) {
        $posts = select_query($con, 'SELECT p.*, u.login, u.*, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ' . $sorting_order . ' LIMIT ' . $limit . ' OFFSET ' . $page);
        print($page);
    } else {
        $page = 0;
        $posts = select_query($con, 'SELECT p.*, u.login, u.*, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ' . $post_type_query . ' ' . $sorting_order . ' LIMIT ' . $limit . ' OFFSET ' . $page);

    }
}

// Если постов больше 9, вывести 9 постов и кнопки пагинации;
// Вычислить количество кнопок пагинации;
// Сгенерировать ссылки на кнопках пагинации;

if (!$posts) {
    open_404_page($user_name, $avatar);
}

get_like($con);

// Передает данные из БД в шаблоны;
$page_content = include_template('main.php', [
    'posts' => $posts,
    'content_types' => $content_types,
    'post_type' => $post_type,
    'sorting_type' => $sorting_type,
    'sorting_direction' => $sorting_direction,
    'pages_count' => $pages_count,
    'current_page' => $current_page,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: популярное',
  'content' => $page_content,
]);

echo($layout_content);
