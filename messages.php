<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];
$user_id = $_SESSION['user']['id'];

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
  // Сохраняет сообщение в таблицу messages;
  date_default_timezone_set('Asia/Yekaterinburg');
  $date = date("Y-m-d H:i:s");

  $chat_message_query = "INSERT INTO messages (date_add, content, sender_id, recipient_id) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($con, $chat_message_query);
  mysqli_stmt_bind_param($stmt, 'ssii', $date, $chat_message, $user_id, $recipient_id);
  mysqli_stmt_execute($stmt);

  header('Location: /messages.php?user=' . $recipient_id);
  exit();
} 

// Проверяет есть ли в таблице chats получатель, если нет - записывает id отправителя и получателя;
$current_chat = select_query($con, "SELECT chat_recipient_id FROM chats ch INNER JOIN users u ON u.id = ch.chat_sender_id WHERE chat_sender_id = '$user_id' AND chat_recipient_id ='$recipient_id'");
if (empty($current_chat)) {
  // Сохраняет чат с пользователем в таблицу chats;
  $chats_query = "INSERT INTO chats (chat_sender_id, chat_recipient_id) VALUES (?, ?)";
  $stmt = mysqli_prepare($con, $chats_query);
  mysqli_stmt_bind_param($stmt, 'ii', $user_id, $recipient_id);
  mysqli_stmt_execute($stmt);
}
$chats = select_query($con, "SELECT * FROM chats ch INNER JOIN users u ON u.id = ch.chat_recipient_id WHERE chat_sender_id = '$user_id'");

// Передает данные из БД в шаблоны;
$page_content = include_template('messages.php', [
  'avatar' => $avatar,
  'recipient_id' => $recipient_id,
  'recipient_email' => $recipient_email,
  'recepient_name' => $recepient_name,
  'recipient_avatar' => $recipient_avatar,
  'chat_messages' => $chat_messages,
  'sender_avatars' => $sender_avatars,
  'chats' => $chats,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: личные сообщения',
  'content' => $page_content,
]);

echo($layout_content);
