<?php
date_default_timezone_set('Asia/Yekaterinburg');
require_once('helpers.php');

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$posts = [
    [
    'title' =>'Полезный пост про Байкал',
    'type' =>'post-text',
    'description' => 'Озеро Байкал - огромное древнее озеро в горах Сибири к северу от монгольской границы. Оно считается самым глубоким в мире. Его средняя глубина - 744 метра. Двадцать первого ноября уровень воды в Байкале составил почти 456 метров.Проблема снижающегося уровня воды в Байкале неоднократно поднималась. Сейчас он меньше порогового значения на шесть сантиметров. Для остановки оттока воды из озера власти предпринимают экономические решения. За последние 60 лет колебания уровня воды в Байкале, близкие к критической отметке, наблюдались 15 раз. Так, в 1981 году уровень Байкала опустился на 70 сантиметров ниже критической отметки.',
    'user_name' => 'Лариса',
    'avatar' => 'userpic-larisa-small.jpg',
  ],
  [
    'title' =>'Цитата',
    'type' =>'post-quote',
    'description' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
    'user_name' => 'Лариса',
    'avatar' => 'userpic-larisa-small.jpg',
  ],
  [
    'title' =>'Игра престолов',
    'type' =>'post-text',
    'description' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
    'user_name' => 'Владик',
    'avatar' => 'userpic.jpg',
  ],
  [
    'title' =>'Наконец, обработал фотки!',
    'type' =>'post-photo',
    'description' => 'rock-medium.jpg',
    'user_name' => 'Виктор',
    'avatar' => 'userpic-mark.jpg',
  ],
  [
    'title' =>'Моя мечта',
    'type' =>'post-photo',
    'description' => 'coast-medium.jpg',
    'user_name' => 'Лариса',
    'avatar' => 'userpic-larisa-small.jpg',
  ],
  [
    'title' =>'Лучшие курсы',
    'type' =>'post-link',
    'description' => 'www.htmlacademy.ru',
    'user_name' => 'Владик',
    'avatar' => 'userpic.jpg',
  ],
];

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

// Нужно сделать округление периодов в большую сторону;
function get_post_interval($post_time) {
  $current_time = date_create();
  $interval = date_diff(date_create($post_time), $current_time);

  $years = $interval->y;
  $months = $interval->m;
  $weeks = $days / 7;
  $days = $interval->d;
  $hours = $interval->h;
  $minutes = $interval->i;

  if ($years) {
    $time = $years . ' ' . get_noun_plural_form($years, "год", "года", "лет") . " назад";
  } else if ($months) {
    $time = $months . ' ' . get_noun_plural_form($months, "месяц", "месяца", "месяцев") . " назад";
  } else if ($days > 7) {
    $time = $weeks . ' ' . get_noun_plural_form($weeks, "неделя", "недели", "недель") . " назад";
  } else if ($days) {
    $time = $days . ' ' . get_noun_plural_form($days, "день", "дня", "дней") . " назад";
  } else if ($hours) {
    $time = $hours . ' ' . get_noun_plural_form($hours, "час", "часа", "часов") . " назад";
  } else if ($minutes) {
    $time = $minutes . ' ' . get_noun_plural_form($minutes, "минута", "минуты", "минут") . " назад";
  } else {
    $time = 'Только что';
  }

  return $time;
}

$page_content = include_template('main.php', ['posts' => $posts]);

$layout_content = include_template('layout.php', [
  'is_auth' => $is_auth,
  'user_name' => $user_name,
  'title' => 'readme: популярное',
  'content' => $page_content,
]);

echo($layout_content);
