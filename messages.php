<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];
$user_id = $_SESSION['user']['id'];
$date = date("Y-m-d H:i:s");

// Получаю данные собеседника из базы;
$recipient_id = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
$recipient = select_query($con, "SELECT * FROM users WHERE id = " . $recipient_id);
$chat_user_login = $recipient[0]['login'];
$chat_user_avatar = $recipient[0]['avatar'];

$chat_message = $_POST['chat-message'];

if (!empty($chat_message)) {
  $chat_message_query = "INSERT INTO messages (date_add, content, sender_id, recipient_id) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($con, $chat_message_query);
  mysqli_stmt_bind_param($stmt, 'ssii', $date, $chat_message, $user_id, $recipient_id);
  mysqli_stmt_execute($stmt);
}

// Получаю сообщения из базы;
$recipient = select_query($con, "SELECT * FROM users WHERE id = " . $recipient_id);
$chat_messages = select_query($con, "SELECT * FROM messages WHERE sender_id = '$user_id' AND recipient_id = '$recipient_id'");

// Получаю данные отправителя и получателя;

// Отправка сообщения;
// Запись сообщения в базу данных - в таблицу messages;
//print_r($chat_messages);
print_r($_POST['chat-message']);


//print_r($chat_user[0]['login']);

// Передает данные из БД в шаблоны;
$page_content = include_template('messages.php', [
  'avatar' => $avatar,
  'chat_user_avatar' => $chat_user_avatar,
  'chat_user_login' => $chat_user_login,
  'chat_user_id' => $recipient_id,
  'chat_messages' => $chat_messages,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: личные сообщения',
  'content' => $page_content,
]);

echo($layout_content);
