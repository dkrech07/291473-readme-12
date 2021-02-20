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
$recipient_email = $recipient[0]['email'];
$recepient_name = $recipient[0]['login'];
$recipient_avatar = $recipient[0]['avatar'];

// Получает сообщения из базы;
$recipient = select_query($con, "SELECT * FROM users WHERE id = " . $recipient_id);
$chat_messages = select_query($con, "SELECT * FROM messages m INNER JOIN users u ON u.id = m.sender_id WHERE sender_id = '$user_id' AND recipient_id = '$recipient_id' OR sender_id = '$recipient_id' AND recipient_id = '$user_id'");

foreach($chat_messages as $chat_message_number => $chat_message) {
  $sender_names[] = $chat_message['login'];
  $sender_avatars[] = $chat_message['avatar'];
}

// Записывает сообщения в базу;
$chat_message = $_POST['chat-message'];
if (!empty($chat_message)) {
  $chat_message_query = "INSERT INTO messages (date_add, content, sender_id, recipient_id) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($con, $chat_message_query);
  mysqli_stmt_bind_param($stmt, 'ssii', $date, $chat_message, $user_id, $recipient_id);
  mysqli_stmt_execute($stmt);
  header('Location: /messages.php?user=' . $recipient_id);
  exit();
} else {
  header('refresh: 5');
}

// Передает данные из БД в шаблоны;
$page_content = include_template('messages.php', [
  'avatar' => $avatar,
  'recipient_id' => $recipient_id,
  'recipient_email' => $recipient_email,
  'recepient_name' => $recepient_name,
  'recipient_avatar' => $recipient_avatar,
  'chat_messages' => $chat_messages,
  'sender_avatars' => $sender_avatars,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: личные сообщения',
  'content' => $page_content,
]);

echo($layout_content);
