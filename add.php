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

if (!$current_content_type_id) {
    $current_content_type_id = 1;
}

function check_validity() {
  $post_type = $_POST ? $_POST['content-type'] : 1;
  $errors = [];

  if ($post_type == 'text') {
    $required_fields = ['text-heading', 'text-content'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }

        if ($_POST[$field] > 70) {
            $errors[$field] = 'Не должна превышать 70 знаков.';
        }
    }
  }

  if ($post_type == 'quote') {
    $required_fields = ['quote-heading', 'quote-content', 'quote-author'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }

        if ($_POST[$field] > 70) {
            $errors[$field] = 'Не должна превышать 70 знаков.';
        }
    }
  }

  if ($_POST && count($errors)) {
      return $errors;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Location: /post.php?id=1');
  }
}

$errors = check_validity('text');
// print_r($errors);
// print_r($_POST);
// print_r($_SERVER);

// echo($content_type['class_name']);
// echo($current_content_type_id);

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
    "current_content_type_id" => $current_content_type_id,
]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: добавить публикацию',
  'content' => $page_content,
]);

echo($layout_content);
