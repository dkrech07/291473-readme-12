<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

// Подключается к БД;
$con = mysqli_connect('localhost', 'root', 'root','readme') or trigger_error('Ошибка подключения: '.mysqli_connect_error(), E_USER_ERROR);

// Получает спиок типов контента для дальнейшего вывода на странице;
$content_types = select_query($con, 'SELECT * FROM content_types');

// Получает ID типа конента из параметра запроса (временно задал значение '1');
$current_content_type_id = filter_input(INPUT_GET, 'post_type', FILTER_VALIDATE_INT);

// Получает ID типа контента, когда в адресной строке нет параметра запроса:
// при первом открытии add.php, после отправки формы и перехода на add.php;
if (!$current_content_type_id) {
    if ($_POST) {
      $current_content_type_id = $_POST['content-type'];
    } else {
      $current_content_type_id = 1;
    }
}

$fields_map = [
    'text-heading' => 'Заголовок. ',
    'text-content' => 'Публикация. ',
    'quote-heading' => 'Заголовок. ',
    'quote-content' => 'Цитата. ',
    'quote-author' => 'Автор. ',
    'photo-heading' => 'Заголовок. ',
    'photo-link' => 'Ссылка. ',
    'userpic-file-photo' => 'Изображение. ',
    'video-heading' => 'Заголовок. ',
    'video-link' => 'Ссылка. ',
    'link-heading' => 'Заголовок. ',
    'link-content' => 'Ссылка. ',
];

$errors = check_validity($current_content_type_id, $fields_map);
// print_r($errors);
// print_r($_POST);

// Получает выбранный тип конента ;
$content_type = select_query($con, 'SELECT * FROM content_types WHERE id = ' . $current_content_type_id, 'assoc');

if (!$content_type) {
  open_404_page($is_auth, $user_name);
}

// Передает данные из БД в шаблоны;
$add_content = include_template('add-' . $content_type['class_name'] . '.php', [
    'content_type' => $content_type,
    'errors' => $errors,
]);

// Передает данные из БД в шаблоны;
$page_content = include_template('add.php', [
    'add_content' => $add_content,
    'content_types' => $content_types,
    "current_content_type_id" => $current_content_type_id,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: добавить публикацию',
  'content' => $page_content,
]);

echo($layout_content);
