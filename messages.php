<?php
require_once('helpers.php');
require_once('includes/functions.inc.php');
require_once('includes/db_connect.inc.php');

session_start();
check_authentication();
$user_name = $_SESSION['user']['login'];
$avatar = $_SESSION['user']['avatar'];
$user_id = $_SESSION['user']['id'];

date_default_timezone_set('Asia/Yekaterinburg');
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

$all_chats_messages = select_query($con, "SELECT * FROM messages m INNER JOIN users u ON u.id = m.sender_id WHERE sender_id = '$user_id' AND recipient_id = '$recipient_id' OR sender_id = '$recipient_id' AND recipient_id = '$user_id'");


foreach($chat_messages as $chat_message_number => $chat_message) {
  $sender_names[] = $chat_message['login'];
  $sender_avatars[] = $chat_message['avatar'];
}

// Записывает сообщения в базу;
$chat_message = $_POST['chat-message'];
if (!empty($chat_message)) {
  // Сохраняет сообщение в таблицу messages;

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

// Здесь нужно получить список всех послединх сообщений (для всем чатов пользователя);
print_r($chats);
foreach ($chats as $chat_number => $chat) {
  $chats_users_ids[] = $chat['chat_recipient_id'];
}
$all_chats = 
$all_chat_messages = select_query($con, "SELECT * FROM messages m INNER JOIN users u ON u.id = m.sender_id WHERE sender_id = '$user_id' AND recipient_id = '$recipient_id' OR sender_id = '$recipient_id' AND recipient_id = '$user_id'");

$last_message = $chat_messages[count($chat_messages) - 1]['content'];
if (mb_strlen($last_message) < 16) {
  $message_preview = substr($last_message, 0, 16);
} else {
  $message_preview = $last_message . '...';
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
  'chats' => $chats,
  'message_preview' => $message_preview,
]);

$layout_content = include_template('layout.php', [
  'user_name' => $user_name,
  'avatar' => $avatar,
  'title' => 'readme: личные сообщения',
  'content' => $page_content,
]);

echo($layout_content);
