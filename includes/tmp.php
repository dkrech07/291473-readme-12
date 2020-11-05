<?php

function send_add_form_data() {
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
      $photo_link = $_POST['photo-link'];
      $file_path = 'uploads/';

      // Если при загрузке картинки из дропзоны получена ошибка;
      if ($_FILES['userpic-file-photo']['error'] != 0) {
        $photo_link = $_POST['photo-link'];
        $file_name = check_loaded_image($photo_link, $posts_count);
        $file_url = 'uploads/' . $file_name;
      // В случае, если была выполнена загрузка из дропзоны;
    } else if ($_FILES['userpic-file-photo']['error'] == 0) {
        $file_name = $_FILES['userpic-file-photo']['name'];
        $file_path = 'uploads/';
        $file_url = 'uploads/' . $file_name;

        move_uploaded_file($_FILES['userpic-file-photo']['tmp_name'], $file_path . $file_name);
      }

      $post_query = "INSERT INTO posts (id, date_add, title, content, image, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$file_name', '$file_url', 0, 1, 3)";
    }

    if ($_POST['content-type'] == 4) {
      $title = $_POST['video-heading'];
      $video_link = $_POST['video-link'];
      $tags_line = $_POST['video-tags'];
      $video = check_loaded_video($video_link);

      $post_query = "INSERT INTO posts (id, date_add, title, content, video, views, post_author_id, content_type_id) VALUES ('$posts_count', '$date', '$title', '$video', '$video', 0, 1, 4)";
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
