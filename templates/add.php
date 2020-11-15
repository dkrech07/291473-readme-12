<?php
require_once('includes/functions.inc.php');
?>

<div class="page__main-section">
  <div class="container">
    <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
  </div>
  <div class="adding-post container">
    <div class="adding-post__tabs-wrapper tabs">
      <div class="adding-post__tabs filters">
        <ul class="adding-post__tabs-list filters__list tabs__list">
          <?php foreach($content_types as $content_type): ?>
          <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--<?= $content_type['class_name'] ?> <?= get_filter_active($current_content_type_id, $content_type) ?> tabs__item tabs__item--active button" href="?post_type=<?= $content_type['id'] ?>">
              <svg class="filters__icon" width="22" height="18">
                <use xlink:href="#icon-filter-<?= $content_type['class_name'] ?>"></use>
              </svg>
              <span><?= $content_type['type_name'] ?></span>
            </a>
          </li>
        <?php endforeach; ?>
        </ul>
      </div>
      <div class="adding-post__tab-content">
        <section class="adding-post__photo tabs__content tabs__content--active">
        <?= $add_content ?>
        
        <!--Вынес дропзону отдельно от формы для сохранения картинки;-->
        <?php if ($current_content_type_id == 3): ?>
          <div class="adding-post__input-file-container form__input-container form__input-container--file">
            <div class="adding-post__input-file-wrapper form__input-file-wrapper">
              <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title=" ">
                <div class="form__file-zone-text">
                  <span>Перетащите фото сюда</span>
                </div>
              </div>
              <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                <span>Выбрать фото</span>
                <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                  <use xlink:href="#icon-attach"></use>
                </svg>
              </button>
            </div>
            <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

            </div>
          </div>
        <? endif; ?>
        </section>
      </div>
    </div>
  </div>
</div>
<script src="js/custom.js"></script>
<script src="js/custom-add.js"></script>
