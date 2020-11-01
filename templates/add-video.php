<?php
$title = $_POST['video-heading'] ?? '';
$link = $_POST['video-link'] ?? '';
$tags = $_POST['video-tags'] ?? '';
?>

<h2 class="visually-hidden">Форма добавления видео</h2>
<form class="adding-post__form form" action="add.php" method="post" enctype="multipart/form-data">
  <div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="video-heading">Заголовок <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['video-heading'] ? 'form__input-section--error' : '' ?>">
          <input class="adding-post__input form__input" id="video-heading" type="text" name="video-heading" value="<?= $title ?>" placeholder="Введите заголовок">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['video-heading'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['video-link'] ? 'form__input-section--error' : '' ?>">
          <input class="adding-post__input form__input" id="video-url" type="text" name="video-link" value="<?= $link ?>" placeholder="Введите ссылку">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['video-link'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="video-tags">Теги</label>
        <div class="form__input-section">
          <input class="adding-post__input form__input" id="video-tags" type="text" name="video-tags" value="<?= $tags ?>" placeholder="Введите ссылку">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc">Текст сообщения об ошибке, подробно объясняющий, что не так.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="form__invalid-block">
      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
      <ul class="form__invalid-list">
        <?php if ($errors): ?>
          <?php foreach($errors as $error): ?>
              <li class="form__invalid-item"><?= $error ?></li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <input class="visually-hidden" type="text" name="content-type" value="4">
  <div class="adding-post__buttons">
    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
    <a class="adding-post__close" href="#">Закрыть</a>
  </div>
</form>
