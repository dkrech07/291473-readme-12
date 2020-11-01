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
        </section>
      </div>
    </div>
  </div>
</div>
<script src="js/custom.js"></script>
