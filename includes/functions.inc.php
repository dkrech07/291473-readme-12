<?php
function select_query($con, $sql, $type = 'all') {
  mysqli_set_charset($con, "utf8");
  $result = mysqli_query($con, $sql) or trigger_error("Ошибка в запросе к базе данных: ".mysqli_error($con), E_USER_ERROR);

  if ($type == 'assoc') {
    return mysqli_fetch_assoc($result);
  }

  if ($type == 'row') {
    return mysqli_fetch_row($result)[0];
  }

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

function get_post_interval($post_time, $caption) {
  $current_time = date_create();
  $interval = date_diff(date_create($post_time), $current_time);

  $years = floor($interval->y);
  $months = floor($interval->m);
  $days = floor($interval->d);
  $weeks = floor($days / 7);
  $hours = floor($interval->h);
  $minutes = floor($interval->i);

  if ($years) {
    $time = $years . ' ' . get_noun_plural_form($years, 'год', 'года', 'лет') . ' ' . $caption;
  } else if ($months) {
    $time = $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' ' . $caption;
  } else if ($days > 7) {
    $time = $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' ' . $caption;
  } else if ($days) {
    $time = $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней') . ' ' . $caption;
  } else if ($hours) {
    $time = $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' ' . $caption;
  } else if ($minutes) {
    $time = $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' ' . $caption;
  } else {
    $time = 'Только что';
  }

  return $time;
}

function open_404_page($is_auth, $user_name) {
  $page_content = include_template('page_404.php');
  $layout_content = include_template('layout.php', [
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'readme: страница не найдена',
    'content' => $page_content,
  ]);

  echo($layout_content);
  http_response_code(404);
  exit();
}

function get_filter_active($current_content_type_id, $content_type) {
  if ($current_content_type_id == $content_type['id']) {
    return 'filters__button--active';
  }
}

// Проверяет загружаемое по ссылке изображение;
function check_loaded_image($photo_link, $posts_count) {

  $check_photo_format = filter_var($photo_link, FILTER_VALIDATE_URL);
  if (!$check_photo_format) {
    exit;
  }
  // Полуачет расширение изображения
  $file = new SplFileInfo($photo_link);
  $extension = $file->getExtension();

  // Проверяет совпадает ли расширение загружаемого файла с одним из трех;
  if ($extension == 'png' || 'jpg' || 'gif') {
      $file_name = 'img-' . $posts_count . '.' . $extension;
      $file_url = 'uploads/' . $file_name;
      // Сохранает изображение на сервере;
      file_put_contents($file_url, file_get_contents($photo_link));
      // Возвращает ссылку на сохраненное изображение;
      return $file_name;
  }
  // Вовращает false, если проверка на соответствие расширения файла не пройдена;
  return false;
}

// Проверяет ссылку на youtube-видео;
function check_loaded_video($video_link) {
    $check_video_format = filter_var($video_link, FILTER_VALIDATE_URL);
    $check_video_link = check_youtube_url($video_link);

    if ($check_video_format && $check_video_link) {
        return $video_link;
    }
}

// Добаляет хештеги в таблицу хештегов / Не добавляет ничего, если хештегов нет;
function get_hashtags($tags_line, $posts_count, $con) {
  if ($tags_line) {
      // Разделяет хештеги по пробелам;
      $tags = explode(' ', $tags_line);
      foreach ($tags as $tag_key => $tag) {

        // Получает ID последнего созданного хештега;
        $hastags_query = "SELECT id FROM hashtags";
        $new_hastag_id = mysqli_num_rows(mysqli_query($con, $hastags_query)) + 1;

        // Записывает теги по одному в таблицу хештегов;;
        $hastags_query = "INSERT INTO hashtags (id, hashtag_name) VALUES ('$new_hastag_id', '$tag')";
        mysqli_query($con, $hastags_query);

        // Записывает id созданного хештега в таблицу с соответствиями поста и хештегов;
        $hastags_id_post_query = "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES ('$new_hastag_id', '$posts_count')";
        mysqli_query($con, $hastags_id_post_query);
      }
  }
}


// Получает имена хештегов из таблицы по их id;
function get_hastag_name($con, $hashtags_id) {
  $post_hashtags = [];

  foreach ($hashtags_id as $hashtag_index => $hashtag_id) {
    $hashtags_name = select_query($con, 'SELECT hashtag_name FROM hashtags WHERE id = ' . $hashtag_id['hashtag_id']);
    $post_hashtags[$hashtag_index] = $hashtags_name[0]['hashtag_name'];
  }

  return $post_hashtags;
}

// Проверяет пустые обязательные поля;
function check_empty_field($required_fields, $fields_map, $errors) {
  foreach ($required_fields as $field) {
      if (empty($_POST[$field])) {
          $errors[$field] = $fields_map[$field] . 'Поле не заполнено.';
      }
  }
  return $errors;
}

// Выполняет валидацию пяти вариантов форм создания поста;
function check_validity($current_content_type_id, $fields_map) {
  date_default_timezone_set('Asia/Yekaterinburg');
  $con = mysqli_connect('localhost', 'root', 'root','readme');
  $date = date("Y-m-d H:i:s");

  // Полуает id для нового поста;
  $posts_count_query = "SELECT id FROM posts";
  $posts_count = mysqli_num_rows(mysqli_query($con, $posts_count_query)) + 1;

  // Пустой массив для ошибок;
  $errors = [];

  if ($_POST && $current_content_type_id == 1) {
    $required_fields = ['text-heading', 'text-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      $title = $_POST['text-heading'];
      $content = $_POST['text-content'];
      $tags_line = $_POST['text-tags'];
      get_hashtags($tags_line, $posts_count, $con);
      $post_query = "INSERT INTO posts (id, date_add, title, content, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$content', 0, 1, 1)";
    }
  }

  if ($_POST && $current_content_type_id == 2) {
    $required_fields = ['quote-heading', 'quote-content', 'quote-author',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      $title = $_POST['quote-heading'];
      $content = $_POST['quote-content'];
      $author = $_POST['quote-author'];
      $tags_line = $_POST['quote-tags'];
      get_hashtags($tags_line, $posts_count, $con);
      $post_query = "INSERT INTO posts (id, date_add, title, content, quote_author, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$content', '$author', 0, 1, 2)";
    }
  }

  if ($_POST && $current_content_type_id == 3) {
    $required_fields = ['photo-heading',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    // Доп. проверка на случае, если оба поля пустые;
    if (empty($_POST['photo-link']) && $_FILES['userpic-file-photo']['error'] != 0) {
      $errors['photo-link'] = $fields_map['photo-link'] . 'Поле не заполнено.';
    }
  }

  if ($_POST && $current_content_type_id == 4) {
    $required_fields = ['video-heading', 'video-link',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    // Доп. проверка на формат ссылки;
    $check_video_format = filter_var($_POST['video-link'], FILTER_VALIDATE_URL);
    if (!$check_video_format) {
      $errors['video-link'] = $fields_map['video-link'] . 'Неверный формат ссылки.';
    }
  }

  if ($_POST && $current_content_type_id == 5) {
    $required_fields = ['link-heading', 'link-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if (count($errors)) {
    // Возвращает список ошибок, если они есть;
    return $errors;
  }

  if (isset($post_query)) {
    // Записывает данные поста в БД;
    // mysqli_query($con, $post_query);
    // // Переходит на страницу с созданным постом;
    // header('Location: post.php?id=' . $posts_count);
  }
}
