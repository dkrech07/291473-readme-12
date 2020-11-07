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
  $check_photo_link_format = filter_var($photo_link, FILTER_VALIDATE_URL);
  if (!$check_photo_link_format) {
    return false;
  }

  $file = new SplFileInfo($photo_link);
  $extension = $file->getExtension();

  if ($extension == 'png' || $extension == 'jpg' || $extension == 'gif') {
      $file_name = 'img-' . $posts_count . '.' . $extension;
      $file_url = 'uploads/' . $file_name;
      file_put_contents($file_url, file_get_contents($photo_link));
      return $file_name;
  }

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

// Получает хештеги для вывода в посте;
function get_hastag_name($con, $hashtags_id) {
  $post_hashtags = [];

  foreach ($hashtags_id as $hashtag_index => $hashtag_id) {
    $hashtags_name = select_query($con, 'SELECT hashtag_name FROM hashtags WHERE id = ' . $hashtag_id['hashtag_id']);
    $post_hashtags[$hashtag_index] = $hashtags_name[0]['hashtag_name'];
  }

  return $post_hashtags;
}

// Добаляет хештеги в БД / Не добавляет ничего, если хештегов нет;
function get_hashtags($tags_line, $posts_count, $con) {
  if ($tags_line) {
      $tags = explode(' ', $tags_line);
      foreach ($tags as $tag_key => $tag) {

          $hastags_query = "SELECT id FROM hashtags";
          $new_hastag_id = mysqli_num_rows(mysqli_query($con, $hastags_query)) + 1;

          // $hastags_query = "INSERT INTO hashtags (id, hashtag_name) VALUES ('$new_hastag_id', '$tag')";
          $hastags_sql = "INSERT INTO hashtags (id, hashtag_name) VALUES (?, ?)";
          $stmt = mysqli_prepare($con, $hastags_sql);
          mysqli_stmt_bind_param($stmt, 'is', $new_hastag_id, $tag);
          mysqli_stmt_execute($stmt);

          $hastags_id_post_sql = "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES (?, ?)";
          $stmt = mysqli_prepare($con, $hastags_id_post_sql);
          mysqli_stmt_bind_param($stmt, 'ii', $new_hastag_id, $posts_count);
          mysqli_stmt_execute($stmt);
      }
  }
}

function check_empty_field($required_fields, $fields_map, $errors) {
  foreach ($required_fields as $field) {
      if (empty($_POST[$field])) {
          $errors[$field] = $fields_map[$field] . 'Поле не заполнено.';
      }
  }
  return $errors;
}

function check_validity($current_content_type_id, $fields_map) {
  date_default_timezone_set('Asia/Yekaterinburg');
  $con = mysqli_connect('localhost', 'root', 'root','readme');
  $date = date("Y-m-d H:i:s");
  $posts_count_query = "SELECT id FROM posts";
  $posts_count = mysqli_num_rows(mysqli_query($con, $posts_count_query)) + 1;
  $errors = [];

  if ($_POST && $current_content_type_id == 1) {
    $required_fields = ['text-heading', 'text-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {

      $title = $_POST['text-heading'];
      $content = $_POST['text-content'];
      $tags_line = $_POST['text-tags'];
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 1;
      // $post_query = "INSERT INTO posts (id, date_add, title, content, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$content', 0, 1, 1)";
      $sql = "INSERT INTO posts (id, date_add, title, content, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, 'isssiii', $posts_count, $date, $title, $content, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
      header('Location: post.php?id=' . $posts_count);

      get_hashtags($tags_line, $posts_count, $con);
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
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 2;

      $sql = "INSERT INTO posts (id, date_add, title, content, quote_author, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, 'isssiii', $posts_count, $date, $title, $content, $author, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
      header('Location: post.php?id=' . $posts_count);

      get_hashtags($tags_line, $posts_count, $con);
    }
  }

  if ($_POST && $current_content_type_id == 3) {
    $required_fields = ['photo-heading',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    // Доп. проверка на формат ссылки / формата изображения;
    $check_photo_link_format = filter_var($_POST['photo-link'], FILTER_VALIDATE_URL);
    if (!$check_photo_link_format) {
        $errors['photo-link'] = $fields_map['photo-link'] . 'Неверный формат ссылки.';
    }

    // Доп. проверка на случай, если поле для ссылки и дропзона пустые;
    if (empty($_POST['photo-link']) && $_FILES['userpic-file-photo']['error'] != 0) {
      $errors['photo-link'] = $fields_map['photo-link'] . 'Поле не заполнено.';
    }

    if (empty($errors)) {
      $title = $_POST['photo-heading'];
      $tags_line = $_POST['photo-tags'];

      $file_name = check_loaded_image($_POST['photo-link'], $posts_count);
      $file_url = 'uploads/' . $file_name;

      $views = 0;
      $post_author_id = 1;
      $content_type_id = 3;

      $sql = "INSERT INTO posts (id, date_add, title, content, image, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, 'issssiii', $posts_count, $date, $title, $file_name, $file_url, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
      header('Location: post.php?id=' . $posts_count);

      get_hashtags($tags_line, $posts_count, $con);
      // Загрузка фотографии из дропзоны;
      // $file_name = $_FILES['userpic-file-photo']['name'];
      // $file_path = 'uploads/';
      // $file_url = 'uploads/' . $file_name;
      // move_uploaded_file($_FILES['userpic-file-photo']['tmp_name'], $file_path . $file_name);
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

    if (empty($errors)) {
      $title = $_POST['video-heading'];
      $video_link = $_POST['video-link'];
      $tags_line = $_POST['video-tags'];
      $video = check_loaded_video($video_link);

      $views = 0;
      $post_author_id = 1;
      $content_type_id = 4;

      $sql = "INSERT INTO posts (id, date_add, title, content, video, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, 'issssiii', $posts_count, $date, $title, $video, $video, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
      header('Location: post.php?id=' . $posts_count);

      get_hashtags($tags_line, $posts_count, $con);
    }
  }

  if ($_POST && $current_content_type_id == 5) {
    $required_fields = ['link-heading', 'link-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      $title = $_POST['link-heading'];
      $link = $_POST['link-content'];
      $tags_line = $_POST['link-tags'];

      $views = 0;
      $post_author_id = 1;
      $content_type_id = 5;

      $sql = "INSERT INTO posts (id, date_add, title, content, link, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $sql);
      mysqli_stmt_bind_param($stmt, 'issssiii', $posts_count, $date, $title, $link, $link, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
      header('Location: post.php?id=' . $posts_count);

      get_hashtags($tags_line, $posts_count, $con);
    }
  }

  if ($_POST && count($errors)) {
      return $errors;
  }

  // // Записывает данные поста в БД;
  // if (isset($post_query)) {
  //   // Записывает созданный пост в таблицу постов;
  //   mysqli_query($con, $post_query);
  //   // Записывает хештеги в таблицу хештегов / в таблицу с сопоставлением хештега и поста;
  //   get_hashtags($tags_line, $posts_count, $con);
  //   // Открыает страницу со созданным постом;
  //   header('Location: post.php?id=' . $posts_count);
  // }


  // Записывает созданный пост в таблицу постов;
  // get_hashtags($tags_line, $posts_count, $con);
  // Открыает страницу со созданным постом;

}
