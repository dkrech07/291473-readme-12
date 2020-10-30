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
            <a class="adding-post__tabs-link filters__button filters__button--<?= $content_type['class_name'] ?> tabs__item tabs__item--active button" href="?post_type=<?= $content_type['id'] ?>">
              <svg class="filters__icon" width="22" height="18">
                <use xlink:href="#icon-filter-<?= $content_type['class_name'] ?>"></use>
              </svg>
              <span><?= $content_type['type_name'] ?></span>
            </a>
          </li>
        <?php endforeach; ?>
          <!-- <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--photo filters__button--active tabs__item tabs__item--active button">
              <svg class="filters__icon" width="22" height="18">
                <use xlink:href="#icon-filter-photo"></use>
              </svg>
              <span>Фото</span>
            </a>
          </li>
          <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--video tabs__item button" href="#">
              <svg class="filters__icon" width="24" height="16">
                <use xlink:href="#icon-filter-video"></use>
              </svg>
              <span>Видео</span>
            </a>
          </li>
          <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--text tabs__item button" href="#">
              <svg class="filters__icon" width="20" height="21">
                <use xlink:href="#icon-filter-text"></use>
              </svg>
              <span>Текст</span>
            </a>
          </li>
          <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--quote tabs__item button" href="#">
              <svg class="filters__icon" width="21" height="20">
                <use xlink:href="#icon-filter-quote"></use>
              </svg>
              <span>Цитата</span>
            </a>
          </li>
          <li class="adding-post__tabs-item filters__item">
            <a class="adding-post__tabs-link filters__button filters__button--link tabs__item button" href="#">
              <svg class="filters__icon" width="21" height="18">
                <use xlink:href="#icon-filter-link"></use>
              </svg>
              <span>Ссылка</span>
            </a>
          </li> -->
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
