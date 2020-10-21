<?php
function select_query($con, $sql) {
  mysqli_set_charset($con, "utf8");

  $result = mysqli_query($con, $sql) or trigger_error("Ошибка в запросе к базе данных: ".mysqli_error($con), E_USER_ERROR);;

  return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

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
