<div class="container">
  <h1 class="page__title page__title--feed">Моя лента</h1>
</div>
<div class="page__main-wrapper container">
  <section class="feed">
    <h2 class="visually-hidden">Лента</h2>
    <div class="feed__main-wrapper">
      <div class="feed__wrapper">
          <?php foreach ($subscription_posts as $post_number => $post): ?>
              <article class="feed__post post post-<?= $post['class_name'] ?>">
                <header class="post__header post__author">
                  <a class="post__author-link" href="profile.php?user=<?=$post['post_author_id']?>" title="Автор">
                    <div class="post__avatar-wrapper">
                      <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя" width="60" height="60">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name"><?= $post['login'] ?></b>
                      <span class="post__time"><?= get_post_interval($post['date_add'], ' назад') ?></span>
                    </div>
                  </a>
                </header>
                <?php require_once('post-' . $post['class_name'] . '.php'); ?>
                <footer class="post__footer post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="?post-id=<?= $post['id'] ?>" title="Лайк">
                      <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                      </svg>
                      <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                      </svg>
                      <span><?= $post['likes_count'] ?></span>
                      <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-comment"></use>
                      </svg>
                      <span>25</span>
                      <span class="visually-hidden">количество комментариев</span>
                    </a>
                    <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                      </svg>
                      <span>5</span>
                      <span class="visually-hidden">количество репостов</span>
                    </a>
                  </div>
                </footer>
              </article>
          <?php endforeach; ?>
      </div>
    </div>
    <ul class="feed__filters filters">
      <li class="feed__filters-item filters__item">
        <a class="filters__button <?= !$post_type ? 'filters__button--active' : '' ?>" href="feed.php">
          <span>Все</span>
        </a>
      </li>
      <?php foreach ($content_types as $content_type): ?>
          <li class="feed__filters-item filters__item">
            <a class="filters__button filters__button--<?= $content_type['class_name'] ?> button <?= $post_type == $content_type[id] ? 'filters__button--active' : '' ?>" href="?post-type=<?= $content_type['id'] ?>">
              <span class="visually-hidden"><?= $content_type['type_name'] ?></span>
              <svg class="filters__icon" width="22" height="18">
                <use xlink:href="#icon-filter-<?= $content_type['class_name'] ?>"></use>
              </svg>
            </a>
          </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <aside class="promo">
    <article class="promo__block promo__block--barbershop">
      <h2 class="visually-hidden">Рекламный блок</h2>
      <p class="promo__text">
        Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
      </p>
      <a class="promo__link" href="#">
        Подробнее
      </a>
    </article>
    <article class="promo__block promo__block--technomart">
      <h2 class="visually-hidden">Рекламный блок</h2>
      <p class="promo__text">
        Товары будущего уже сегодня в онлайн-сторе Техномарт!
      </p>
      <a class="promo__link" href="#">
        Перейти в магазин
      </a>
    </article>
    <article class="promo__block">
      <h2 class="visually-hidden">Рекламный блок</h2>
      <p class="promo__text">
        Здесь<br> могла быть<br> ваша реклама
      </p>
      <a class="promo__link" href="#">
        Разместить
      </a>
    </article>
  </aside>
</div>
