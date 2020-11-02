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

// function check_length_field($fields, $fields_map, $errors) {
//   foreach ($fields as $field) {
//     if (mb_strlen($_POST[$field]) > 70) {
//         $errors[$field] = $fields_map[$field] . 'Не должна превышать 70 знаков.';
//     }
//   }
//   return $errors;
// }

function get_hastag_name($con, $hashtags_id) {
  $post_hashtags = [];
  
  foreach ($hashtags_id as $hashtag_index => $hashtag_id) {
    $hashtags_name = select_query($con, 'SELECT hashtag_name FROM hashtags WHERE id = ' . $hashtag_id['hashtag_id']);
    $post_hashtags[$hashtag_index] = $hashtags_name[0]['hashtag_name'];
  }

  return $post_hashtags;
}

function send_data() {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Yekaterinburg');
    $con = mysqli_connect('localhost', 'root', 'root','readme');
    $date = date("Y-m-d H:i:s");

    // Полуает id для нового поста;
    $posts_count_query = "SELECT id FROM posts";
    $posts_count = mysqli_num_rows(mysqli_query($con, $posts_count_query)) + 1;

    // Проверяет тип поста;
    if ($_POST['content-type'] == 1) {
      $title = $_POST['text-heading'];
      $content = $_POST['text-content'];
      $tags_line = $_POST['text-tags'];

      $post_query = "INSERT INTO posts (id, date_add, title, content, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$content', 0, 1, 1)";
    }

    if ($_POST['content-type'] == 2) {
      $title = $_POST['quote-heading'];
      $content = $_POST['quote-content'];
      $author = $_POST['quote-author'];
      $tags_line = $_POST['quote-tags'];

      $post_query = "INSERT INTO posts (id, date_add, title, content, quote_author, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$content', '$author', 0, 1, 2)";
    }

    if ($_POST['content-type'] == 3) {
      $title = $_POST['photo-heading'];
      $tags_line = $_POST['photo-tags'];

      $post_query = "INSERT INTO posts (id, date_add, title, content, image, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', 'rock-medium.jpg', 'img/rock-medium.jpg', 0, 1, 3)";
    }

    if ($_POST['content-type'] == 4) {
      $title = $_POST['video-heading'];
      $video = $_POST['video-link'];
      $tags_line = $_POST['video-tags'];

      $post_query = "INSERT INTO posts (id, date_add, title, content, video, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', 'https://www.youtube.com/watch?v=iKdu4Enctq4', 'https://www.youtube.com/watch?v=iKdu4Enctq4', 0, 1, 4)";
    }

    if ($_POST['content-type'] == 5) {
      $title = $_POST['link-heading'];
      $link = $_POST['link-content'];
      $tags_line = $_POST['photo-tags'];

      $post_query = "INSERT INTO posts (id, date_add, title, content, link, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$link', '$link', 0, 1, 5)";
    }

    // Записывает данные поста в БД;
    mysqli_query($con, $post_query);

    // Добаляет хештеги в БД / Не добавляет ничего, если хештегов нет;
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

    // Открыает страницу со созданным постом;
    header('Location: post.php?id=' . $posts_count);
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
  $errors = [];

  if ($_POST && $current_content_type_id == 1) {
    $required_fields = ['text-heading', 'text-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if ($_POST && $current_content_type_id == 2) {
    $required_fields = ['quote-heading', 'quote-content', 'quote-author',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if ($_POST && $current_content_type_id == 3) {
    $required_fields = ['photo-heading',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if ($_POST && $current_content_type_id == 4) {
    $required_fields = ['video-heading', 'video-link',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if ($_POST && $current_content_type_id == 5) {
    $required_fields = ['link-heading', 'link-content',];
    $errors = check_empty_field($required_fields, $fields_map, $errors);
  }

  if ($_POST && count($errors)) {
      return $errors;
  }

  send_data();
}
