<?php
function select_query($con, $sql, $type = 'all')
{
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

function crop_text($text, $characters_count = 300)
{
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

function get_post_interval($post_time, $caption)
{
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
    } elseif ($months) {
        $time = $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев') . ' ' . $caption;
    } elseif ($days > 7) {
        $time = $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель') . ' ' . $caption;
    } elseif ($days) {
        $time = $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней') . ' ' . $caption;
    } elseif ($hours) {
        $time = $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' ' . $caption;
    } elseif ($minutes) {
        $time = $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' ' . $caption;
    } else {
        $time = 'Только что';
    }

    return $time;
}

function open_404_page($user_name, $avatar)
{
    $page_content = include_template('page_404.php');
    $layout_content = include_template('layout.php', [
    'user_name' => $user_name,
    'avatar' => $avatar,
    'title' => 'readme: страница не найдена',
    'content' => $page_content,
  ]);

    echo($layout_content);
    http_response_code(404);
    exit();
}

function get_filter_active($current_content_type_id, $content_type)
{
    if ($current_content_type_id == $content_type['id']) {
        return 'filters__button--active';
    }
}

// Проверяет загружаемое по ссылке изображение;
function check_loaded_image($photo_link)
{
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
function check_loaded_video($video_link)
{
    $check_video_format = filter_var($video_link, FILTER_VALIDATE_URL);
    if ($check_video_format) {
        return check_youtube_url($video_link);
    }
    return 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
}

// Получает id хештегов по именам;
function get_hashtags_ids($con, $post_tags_names)
{
    return select_query($con, "SELECT id FROM hashtags WHERE (hashtag_name) IN ($post_tags_names)");
}

// Получает названия хештегов по id;
function get_hashtags_names($con, $post_tags_ids)
{
    return select_query($con, "SELECT hashtag_name FROM hashtags WHERE (id) IN ($post_tags_ids)");
}

// Получает хештеги для вывода в посте;
function get_hashtag_name($con, $hashtags_id)
{
    $hashtags_ids = [];
    foreach ($hashtags_id as $hashtag_index => $hashtag_id) {
        $hashtags_ids[] = $hashtags_id[$hashtag_index]['hashtag_id'];
    }

    if (!empty($hashtags_ids)) {
        $ids_list = implode(",", $hashtags_ids);
        $hashtags_names = get_hashtags_names($con, $ids_list);

        $hashtags_list = [];
        foreach ($hashtags_names as $name_number => $name) {
            $hashtags_list[] = $name['hashtag_name'];
        }
        return array_combine($hashtags_ids, $hashtags_list);
    }
}

// Добаляет хештеги в БД / Не добавляет ничего, если хештегов нет;
function get_hashtags($tags_line, $post_id, $con)
{
    $incoming_words = "#" . implode(" ", $tags_line);

    $incoming_tags = explode(' ', $tags_line);
    // Проверяет хештеги на наличие символов/заполнение;
    $verification_result = true;
    foreach ($incoming_tags as $incoming_tags_number => $incoming_tag) {
        $verification_result = $verification_result && preg_match("/^[a-zA-Z0-9а-яА-ЯёЁ]+$/", $incoming_tag);
    }
    if ($verification_result) {
        $verified_tags = "'" . implode("', '", $incoming_tags) . "'";
        // Получает список совпадающих хештегов из БД;
        $old_tags = select_query($con, "SELECT * FROM hashtags WHERE (hashtag_name) IN ($verified_tags)");
        // Сохраняет совпадающие (уже сохраненные в БД) хештеги в массив;
        $already_saved_tags = [];
        foreach ($old_tags as $old_tag_number => $old_tag) {
            $already_saved_tags[] = $old_tag['hashtag_name'];
        }
        // Получает хештеги, которых нет в БД;
        $new_tags = "('" . implode("'), ('", array_diff($incoming_tags, $already_saved_tags)) . "')";
        // И записывает их в таблицу hashtags;
        mysqli_query($con, "INSERT IGNORE INTO hashtags (hashtag_name) VALUES $new_tags");
        // Получает id хештегов записанных в таблицу;
        $new_tags_ids = get_hashtags_ids($con, $verified_tags);
        // Формирует запрос с id хештегов и id текущего поста;
        $tags_query = "";
        foreach ($new_tags_ids as $tags_nubmer => $tag_id) {
            if (!empty($tags_query)) {
                $tags_query .= ",";
            }
            $tags_query .= "({$post_id}, {$tag_id['id']})";
        }
        // Сохраняет id хештегов и постов в таблицу post_hashtags;
        mysqli_query($con, "INSERT INTO post_hashtags (post_id, hashtag_id) VALUES $tags_query");
    }
}

function check_empty_field($required_fields, $fields_map)
{
    $errors = array();
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = $fields_map[$field] . 'Поле не заполнено.';
        }
    }
    return $errors;
}

function check_validity($con, $current_content_type_id, $fields_map)
{
    if (empty($_POST)) {
        return null;
    }

    date_default_timezone_set('Asia/Yekaterinburg');
    $date = date("Y-m-d H:i:s");

    if ($current_content_type_id == 1) {
        $required_fields = ['text-heading', 'text-content',];
        $errors = check_empty_field($required_fields, $fields_map);
        if (!empty($errors)) {
            return $errors;
        }

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

    if ($current_content_type_id == 2) {
        $required_fields = ['quote-heading', 'quote-content', 'quote-author',];
        $errors = check_empty_field($required_fields, $fields_map);
        if (!empty($errors)) {
            return $errors;
        }

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

    if ($current_content_type_id == 3) {
        $required_fields = ['photo-heading',];
        $errors = check_empty_field($required_fields, $fields_map);
        if (!empty($errors)) {
            return $errors;
        }

        // Доп. проверка на случай, если поле для ссылки и дропзона пустые;
        // Временно закомментировал проверку;
        // if (empty($_POST['photo-link']) && $_FILES['userpic-file-photo']['error'] != 0) {
        //   $errors['photo-link'] = $fields_map['photo-link'] . 'Поле не заполнено.';
        // }
        print_r($_FILES);

        $file_name = check_loaded_image($_POST['photo-link']);
        // Доп. проверка на формат ссылки / формата изображения;
        $check_photo_link_format = filter_var($_POST['photo-link'], FILTER_VALIDATE_URL);
        if (!$check_photo_link_format || !$file_name) {
            $errors['photo-link'] = $fields_map['photo-link'] . 'Неверный формат ссылки.';
            return $errors;
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

    if ($current_content_type_id == 4) {
        $required_fields = ['video-heading', 'video-link',];
        $errors = check_empty_field($required_fields, $fields_map);
        if (!empty($errors)) {
            return $errors;
        }

        // Доп. проверка на формат ссылки;
        $video_link = $_POST['video-link'];
        $check_video_link = check_loaded_video($video_link);
        if (check_loaded_video($video_link) != 1) {
            $errors['video-link'] = $fields_map['video-link']  . $check_video_link . '.';
            return $errors;
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

    if ($current_content_type_id == 5) {
        $required_fields = ['link-heading', 'link-content',];
        $errors = check_empty_field($required_fields, $fields_map);
        if (!empty($errors)) {
            return $errors;
        }

        $link = $_POST['link-content'];
        // Доп. проверка на формат ссылки;
        $check_link_format = filter_var($link, FILTER_VALIDATE_URL);
        if (!$check_link_format) {
            $errors['link-content'] = $fields_map['link-content'] . 'Неверный формат ссылки.';
            return $errors;
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

    // Записывает хештеги в таблицу хештегов / переходит на страницу поста;
    $post_id = $con->insert_id;
    get_hashtags($tags_line, $post_id, $con);
    header('Location: /post.php?id=' . $post_id);
    return null;
}

function check_registration_validity($con, $fields_map)
{
    if (empty($_POST)) {
        return null;
    }

    date_default_timezone_set('Asia/Yekaterinburg');
    $date = date("Y-m-d H:i:s");
    $required_fields = ['email', 'login', 'password', 'password-repeat'];
    $errors = check_empty_field($required_fields, $fields_map);

    // Выполняет сохранение данных, если при заполнении формы не допущено ошибок;
    if (empty($errors)) {
        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $password_repeat = $_POST['password-repeat'];
        $avatar = 'img/cat.jpg';

        // Проверяет валидность email-адреса;
        $email_format = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$email_format) {
            $errors['email'] = $fields_map['email'] . 'Неверный формат.';
        }

        // Проверяет соответствие пароля и подтверждения пароля;
        if ($password != $password_repeat) {
            $errors['password'] = $fields_map['password'] . 'Пароль и повтор пароля не совпадают.';
            $errors['password-repeat'] = $fields_map['password-repeat'] . 'Пароль и повтор пароля не совпадают.';
        }

        // Проверяет не занят ли логин или email;
        $safe_login = mysqli_real_escape_string($con, $login);
        $already_saved_email_and_login = select_query($con, "SELECT email, login FROM users WHERE email = '$email' OR login = '$safe_login'");
        if ($already_saved_email_and_login) {
            if ($email == $already_saved_email_and_login[0]['email']) {
                $errors['email'] = $fields_map['email'] . 'Уже есть в системе.';
            }
            if ($login == $already_saved_email_and_login[0]['login']) {
                $errors['login'] = $fields_map['login'] . 'Уже есть в системе.';
            }
        }

        // Дополнительное условия;
        if (preg_match("/\\s/", $password)) {
            $errors['password'] = $fields_map['password'] . 'Не должен содержать пробелы.';
        }

        if (mb_strlen($password) < 8) {
            $errors['password'] = $fields_map['password'] . 'Должен быть не меньше 8 символов.';
        }
    }

    // Возвращает ошибки при их наличии и только после отправки формы;
    if (!empty($errors)) {
        return $errors;
    }

    // Сохраняет данные пользователя в таблицу пользователей;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $post_query = "INSERT INTO users (date_add, email, login, password, avatar) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $post_query);
    mysqli_stmt_bind_param($stmt, 'sssss', $date, $email, $login, $password_hash, $avatar);
    mysqli_stmt_execute($stmt);
    header('Location: /main.html');
    return null;
}

function authenticate($con)
{
    if (empty($_POST)) {
        return null;
    }

    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required = ['login', 'password'];
        $errors = [];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = 'Это поле надо заполнить';
            }
        }

        if (empty($errors)) {
            $login = mysqli_real_escape_string($con, $_POST['login']);
            $user_query = select_query($con, "SELECT id, login, password, avatar FROM users WHERE login = '$login'", 'assoc');
            $user = $user_query ? $user_query : null;

            if (isset($user)) {
                if (password_verify($_POST['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Неверный пароль';
                }
            } elseif (!isset($user)) {
                $errors['login'] = 'Такой пользователь не найден';
            }
        }
    } else {
        $page_content = include_template('feed.php', []);

        if (isset($_SESSION['user'])) {
            header("Location: /index.php");
            exit();
        }
    }

    if (empty($errors)) {
        header("Location: /feed.php");
        exit();
    }

    return $errors;
}

function check_authentication()
{
    if (!isset($_SESSION['user'])) {
        header("Location: /index.php");
        exit();
    }
}
