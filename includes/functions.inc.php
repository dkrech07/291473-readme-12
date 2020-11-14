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
function check_loaded_image($photo_link) {
  $check_photo_link_format = filter_var($photo_link, FILTER_VALIDATE_URL);

  if ($check_photo_link_format) {
    $img_format = exif_imagetype($check_photo_link_format);

    if ($img_format == IMAGETYPE_PNG || $img_format == IMAGETYPE_JPEG || $img_format == IMAGETYPE_GIF) {
      $file_name = htmlspecialchars(basename($photo_link));
      $file_url = 'uploads/' . $file_name;
      file_put_contents($file_url, file_get_contents($photo_link));
      return $file_name;
    }
  }

  return false;
}

// Проверяет ссылку на youtube-видео;
function check_loaded_video($video_link) {
  $check_video_format = filter_var($video_link, FILTER_VALIDATE_URL);
  if ($check_video_format) {
    return check_youtube_url($video_link);
  }
  return 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
}

// Получает хештеги для вывода в посте;
function get_hastag_name($con, $hashtags_id) {
  $hashtags_ids = [];
  foreach ($hashtags_id as $hashtag_index => $hashtag_id) {
    array_push($hashtags_ids, $hashtags_id[$hashtag_index]['hashtag_id']);
  }

  if (!empty($hashtags_ids)) {
    $ids_list = implode(",", $hashtags_ids);
    $hashtags_names = select_query($con, 'SELECT hashtag_name FROM hashtags WHERE id IN (' . $ids_list . ')');

    $hashtags_list = [];
    foreach ($hashtags_names as $name_number => $name) {
      array_push($hashtags_list, $name['hashtag_name']);
    }

    return array_combine($hashtags_ids, $hashtags_list);
  }
}

// Добаляет хештеги в БД / Не добавляет ничего, если хештегов нет;
function get_hashtags($tags_line, $posts_count, $con) {
    $incoming_tags = explode(' ', $tags_line); // Тут будет проверка пользовательских тегов;
    $verified_tags = "'" . implode ( "', '", $incoming_tags ) . "'";

    // Получает список совпадающих хештегов из БД;
    $old_tags = select_query($con, "SELECT * FROM hashtags WHERE (hashtag_name) IN ($verified_tags)");

    // Сохраняет совпадающие хештеги в массив;
    $saved_tags = [];
    foreach ($old_tags as $old_tag_number => $old_tag) {
        array_push($saved_tags, $old_tag['hashtag_name']);
    }

    // Получает хештеги, которых нет в БД;
    $new_tags = "('" . implode ( "'), ('", array_diff($incoming_tags, $saved_tags) ) . "')";

    // И записывает их в таблицу hashtags;
    mysqli_query($con, "INSERT IGNORE INTO hashtags (hashtag_name) VALUES $new_tags");

    // Получает id хештегов записанных в таблицу;
    $new_tags_ids = select_query($con, "SELECT id FROM hashtags WHERE (hashtag_name) IN ($verified_tags)");

    $new_ids = [];
    foreach ($new_tags_ids as $tags_nubmer => $tag_id) {
      $new_ids[$tags_nubmer] = $new_tags_ids[$tags_nubmer]['id'];
    }

    $new_ids_list = '(' . implode ( ', '. $posts_count . '), ' . ' (', $new_ids) . ', ' . $posts_count . ')';

    mysqli_query($con, "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES $new_ids_list");

    // print_r($new_ids_list);
    // print('<br>');
    // print($posts_count);


    // $new_tags =
    // $tags_and_post =


    // $new_ids = [];

    // $new_ids_list = implode ( ", ", $new_ids );
    // $new_ids_list = "('" . implode ( "'), ('", $new_ids ) . "')";

    // print('<br>');
    // print($posts_count);

    // $new_tags_ids = "('" . implode ( "'), ('", $new_tags_ids_query) . "')";
    // print_r($new_ids_list);
    // // Сохраняет id созданных тегов и id поста в котором эти теги созданы в таблицу post_hashtags;
    // mysqli_query($con, "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES ($new_ids_list), ($posts_count)");
    // mysqli_query($con, "INSERT IGNORE INTO post_hashtags (hashtag_id, post_id) VALUES ('500'), ('550')");
    // mysqli_query($con, "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES (354, 5), (353, 5), (355, 5)");



    // print_r($old_tags);
    // print_r('<br>');
    // print_r($new_tags);

    // Добавляет новые хештеги (которых нет в таблице hashtags) в массив;
    // INSERT IGNORE INTO hashtags (hashtag_name) VALUES (имя1, имя2);

  // if ($tags_line) {
  //   $tags = explode(' ', $tags_line);
  //   foreach ($tags as $tag_key => $tag) {
  //
  //     // Сохраняет теги в таблицу hashtags (id тегов и тегами);
  //     $hastags_query = "INSERT IGNORE INTO hashtags (hashtag_name) VALUES (?)";
  //     $stmt = mysqli_prepare($con, $hastags_query);
  //     mysqli_stmt_bind_param($stmt, 's', $tag);
  //     mysqli_stmt_execute($stmt);
  //
  //     // Получает id созданных хештегов;
  //     $new_hastag_id = select_query($con, "SELECT id FROM hashtags WHERE hashtag_name = '$tag'", 'row');
  //
  //     // Сохганяет id созданных тегов и id поста в котором эти теги созданы;
  //     $hastags_id_post_query = "INSERT INTO post_hashtags (hashtag_id, post_id) VALUES (?, ?)";
  //     $stmt = mysqli_prepare($con, $hastags_id_post_query);
  //     mysqli_stmt_bind_param($stmt, 'ii', $new_hastag_id, $posts_count);
  //     mysqli_stmt_execute($stmt);
  //   }
  // }
}

function check_empty_field($required_fields, $fields_map, $errors) {
  foreach ($required_fields as $field) {
      if (empty($_POST[$field])) {
          $errors[$field] = $fields_map[$field] . 'Поле не заполнено.';
      }
  }
  return $errors;
}

function check_validity($con, $current_content_type_id, $fields_map) {
  date_default_timezone_set('Asia/Yekaterinburg');
  $date = date("Y-m-d H:i:s");
  $errors = [];

  if ($current_content_type_id == 1) {
    $required_fields = ['text-heading', 'text-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      $title = $_POST['text-heading'];
      $content = $_POST['text-content'];
      $tags_line = $_POST['text-tags'];
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 1;

      $post_query = "INSERT INTO posts (date_add, title, content, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $post_query);
      mysqli_stmt_bind_param($stmt, 'sssiii', $date, $title, $content, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
    }
  }

  if ($current_content_type_id == 2) {
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

      $post_query = "INSERT INTO posts (date_add, title, content, quote_author, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $post_query);
      mysqli_stmt_bind_param($stmt, 'ssssiii', $date, $title, $content, $author, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
    }
  }

  if ($current_content_type_id == 3) {
    $required_fields = ['photo-heading',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    // Доп. проверка на случай, если поле для ссылки и дропзона пустые;
    if (empty($_POST['photo-link']) && $_FILES['userpic-file-photo']['error'] != 0) {
      $errors['photo-link'] = $fields_map['photo-link'] . 'Поле не заполнено.';
    }

    if (empty($errors)) {
      $file_name = check_loaded_image($_POST['photo-link']);
      // Доп. проверка на формат ссылки / формата изображения;
      $check_photo_link_format = filter_var($_POST['photo-link'], FILTER_VALIDATE_URL);
      if (!$check_photo_link_format || !$file_name) {
          $errors['photo-link'] = $fields_map['photo-link'] . 'Неверный формат ссылки.';
      }

      $title = $_POST['photo-heading'];
      $tags_line = $_POST['photo-tags'];
      $file_url = 'uploads/' . $file_name;
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 3;

      $post_query = "INSERT INTO posts (date_add, title, content, image, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $post_query);
      mysqli_stmt_bind_param($stmt, 'ssssiii', $date, $title, $file_name, $file_url, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);

      // Загрузка фотографии из дропзоны;
      // $file_name = $_FILES['userpic-file-photo']['name'];
      // $file_path = 'uploads/';
      // $file_url = 'uploads/' . $file_name;
      // move_uploaded_file($_FILES['userpic-file-photo']['tmp_name'], $file_path . $file_name);
    }
  }

  if ($current_content_type_id == 4) {
    $required_fields = ['video-heading', 'video-link',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      // Доп. проверка на формат ссылки;
      $video_link = $_POST['video-link'];
      $check_video_link = check_loaded_video($video_link);
      if (check_loaded_video($video_link) != 1) {
        $errors['video-link'] = $fields_map['video-link']  . $check_video_link . '.';
      }

      $title = $_POST['video-heading'];
      $tags_line = $_POST['video-tags'];
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 4;

      $post_query = "INSERT INTO posts (date_add, title, content, video, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $post_query);
      mysqli_stmt_bind_param($stmt, 'ssssiii', $date, $title, $video_link, $video_link, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
    }
  }

  if ($current_content_type_id == 5) {
    $required_fields = ['link-heading', 'link-content',];
    $link = $_POST['link-content'];
    $errors = check_empty_field($required_fields, $fields_map, $errors);

    if (empty($errors)) {
      // Доп. проверка на формат ссылки;
      $check_link_format = filter_var($link, FILTER_VALIDATE_URL);
      if (!$check_link_format) {
        $errors['link-content'] = $fields_map['link-content'] . 'Неверный формат ссылки.';;
      }

      $title = $_POST['link-heading'];
      $tags_line = $_POST['link-tags'];
      $views = 0;
      $post_author_id = 1;
      $content_type_id = 5;

      $post_query = "INSERT INTO posts (date_add, title, content, link, views, post_author_id, content_type_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($con, $post_query);
      mysqli_stmt_bind_param($stmt, 'ssssiii', $date, $title, $link, $link, $views, $post_author_id, $content_type_id);
      mysqli_stmt_execute($stmt);
    }
  }

  // Возвращает ошибки для вывода на странице формы;
  if (!empty($errors)) {
      return $errors;
  }

  // Записывает хештеги в таблицу хештегов / переходит на страницу поста;
  $posts_count = $con->insert_id;
  get_hashtags($tags_line, $posts_count, $con);
  header('Location: post.php?id=' . $posts_count);
}
