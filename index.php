<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$con = mysqli_connect("localhost", "root", "root","readme");
$content_types_select = 'SELECT * FROM content_types';
$posts_select = 'SELECT p.*, u.login, u.avatar, ct.type_name, ct.class_name FROM posts p INNER JOIN users u ON u.id = p.post_author_id INNER JOIN content_types ct ON ct.id = p.content_type_id ORDER BY p.views DESC';

function select_query($con, $sql) {
  mysqli_set_charset($con, "utf8");

  if (!$con) {
    echo ("Ошибка подключения: " . mysqli_connect_error());
    return null;
  }

  $result = mysqli_query($con, $sql);
  if (!$result) {
    return null;
  }

  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$content_types = select_query($con, $content_types_select);
$posts = select_query($con, $posts_select);

function crop_text($text, $characters_count = 300) {
  if (mb_strlen($text) <= $characters_count) {
    return '<p>' . $text . '</p>';
  }

  $words = explode(' ', $text);
  $cropped_text_length = -1;

  foreach ($words as $word_number => $word) {
    $cropped_text_length += mb_strlen($word) + 1;

    if ($cropped_text_length > $characters_count) {
      $cropped_text = implode(' ', array_slice($words, 0, $word_number));
      return '<p>' . $cropped_text . '...</p>' . '<a class="post-text__more-link" href="#">Читать далее</a>';
    }
  }
}

function get_post_interval($post_time) {
  $current_time = date_create();
  $interval = date_diff(date_create($post_time), $current_time);

  $years = floor($interval->y);
  $months = floor($interval->m);
  $days = floor($interval->d);
  $weeks = floor($days / 7);
  $hours = floor($interval->h);
  $minutes = floor($interval->i);

  if ($years) {
    $time = $years . ' ' . get_noun_plural_form($years, 'год', 'года', 'лет') . ' назад';
  } else if ($months) {
    $time = $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' назад';
  } else if ($days > 7) {
    $time = $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' назад';
  } else if ($days) {
    $time = $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней') . ' назад';
  } else if ($hours) {
    $time = $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
  } else if ($minutes) {
    $time = $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' назад';
  } else {
    $time = 'Только что';
  }

  return $time;
}

$page_content = include_template('main.php', ['posts' => $posts, 'content_types' => $content_types]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: популярное',
  'content' => $page_content,
]);

echo($layout_content);
