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
$current_content_type_id = $_GET['post_type'];

if (!$_GET['post_type']) {
    $current_content_type_id = 1;
}

// filter_input(INPUT_GET, 'post_type', FILTER_VALIDATE_INT);

function check_validity($required_fields = ['title', 'content']) {
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    if (mb_strlen($_POST['text-content']) > 70) {
        $errors['content'] = 'Цитата. Она не должна превышать 70 знаков.';
    }

    if ($_POST && count($errors)) {
        return $errors;
    }

    if ($_POST) {
        return $_POST;
    }
}

$errors = check_validity();
print_r($errors);
// print_r($_POST);

// Получает выбранный тип конента ;
$content_type = select_query($con, 'SELECT * FROM content_types WHERE id = ' . $current_content_type_id, 'assoc');

// Передает данные из БД в шаблоны;
$add_content = include_template('add-' . $content_type['class_name'] . '.php', [
    'content_type' => $content_type,
    'errors' => $errors,
]);

// Передает данные из БД в шаблоны;
$page_content = include_template('add.php', [
    'add_content' => $add_content,
    'content_types' => $content_types,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: добавить публикацию',
  'content' => $page_content,
]);

echo($layout_content);
