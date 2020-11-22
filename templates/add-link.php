<?php
$title = $_POST['link-heading'] ?? '';
$link = $_POST['link-content'] ?? '';
$tags = $_POST['link-tags'] ?? '';
?>

<h2 class="visually-hidden">Форма добавления ссылки</h2>
<form class="adding-post__form form" action="add.php" method="post">
  <div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="link-heading">Заголовок <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['link-heading'] ? 'form__input-section--error' : '' ?>">
          <input class="adding-post__input form__input" id="link-heading" type="text" name="link-heading" value="<?= $title ?>" placeholder="Введите заголовок">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['link-heading'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__textarea-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['link-content'] ? 'form__input-section--error' : '' ?>">
          <input class="adding-post__input form__input" id="post-link" type="text" name="link-content" value="<?= $link ?>" placeholder="Введите ссылку">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['link-content'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="link-tags">Теги</label>
        <div class="form__input-section">
          <input class="adding-post__input form__input" id="link-tags" type="text" name="link-tags" value="<?= $tags ?>" placeholder="Введите ссылку">
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
          <?php foreach ($errors as $error): ?>
              <li class="form__invalid-item"><?= $error ?></li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <input class="visually-hidden" type="text" name="content-type" value="5">
  <div class="adding-post__buttons">
    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
    <a class="adding-post__close" href="#">Закрыть</a>
  </div>
</form>
