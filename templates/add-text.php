<?php
$title = $_POST['text-heading'] ?? '';
$content = $_POST['text-content'] ?? '';
$tags = $_POST['text-tags'] ?? '';
?>

<h2 class="visually-hidden">Форма добавления текста</h2>
<form class="adding-post__form form" action="add.php" method="post">
  <div class="form__text-inputs-wrapper">
    <div class="form__text-inputs">
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="text-heading">Заголовок <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['text-heading'] ? 'form__input-section--error' : '' ?>">
          <input class="adding-post__input form__input" id="text-heading" type="text" name="text-heading" value="<?= $title ?>" placeholder="Введите заголовок">
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['text-heading'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__textarea-wrapper form__textarea-wrapper">
        <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
        <div class="form__input-section <?= $errors['text-content'] ? 'form__input-section--error' : '' ?>">
          <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="text-content" placeholder="Введите текст публикации"><?= $content ?></textarea>
          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
          <div class="form__error-text">
            <h3 class="form__error-title">Заголовок сообщения</h3>
            <p class="form__error-desc"><?= $errors['text-content'] ? 'Заполните это поле' : '' ?></p>
          </div>
        </div>
      </div>
      <div class="adding-post__input-wrapper form__input-wrapper">
        <label class="adding-post__label form__label" for="post-tags">Теги</label>
        <div class="form__input-section">
          <input class="adding-post__input form__input" id="post-tags" type="text" name="text-tags" value="<?= $tags ?>" placeholder="Введите теги">
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
        <!-- <li class="form__invalid-item">Заголовок. Это поле должно быть заполнено.</li>
        <li class="form__invalid-item">Цитата. Она не должна превышать 70 знаков.</li> -->
      </ul>
    </div>
  </div>
  <input class="visually-hidden" type="text" name="content-type" value="text">
  <div class="adding-post__buttons">
    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
    <a class="adding-post__close" href="#">Закрыть</a>
  </div>
</form>
