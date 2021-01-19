<div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
</div>
<div class="popular container">
    <div class="popular__filters-wrapper">
        <div class="popular__sorting sorting">
            <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
            <ul class="popular__sorting-list sorting__list">
                <li class="sorting__item sorting__item--popular">
                    <a class="sorting__link <?= $sorting_type == 'popular' ? 'sorting__link--active' : '' ?> <?= $sorting_direction == 'asc' ? 'sorting__link--reverse' : '' ?>" href="?sorting-type=popular&sorting-direction=<?= $sorting_direction !== 'asc' ? 'asc' : 'desc' ?>">
                        <span>Популярность</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link <?= $sorting_type == 'likes' ? 'sorting__link--active' : '' ?> <?= $sorting_direction == 'asc' ? 'sorting__link--reverse' : '' ?>" href="?sorting-type=likes&sorting-direction=<?= $sorting_direction !== 'asc' ? 'asc' : 'desc' ?>">
                        <span>Лайки</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
                <li class="sorting__item">
                    <a class="sorting__link <?= $sorting_type == 'date' ? 'sorting__link--active' : '' ?> <?= $sorting_direction == 'asc' ? 'sorting__link--reverse' : '' ?>" href="?sorting-type=date&sorting-direction=<?= $sorting_direction !== 'asc' ? 'asc' : 'desc' ?>">
                        <span>Дата</span>
                        <svg class="sorting__icon" width="10" height="12">
                            <use xlink:href="#icon-sort"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
        <div class="popular__filters filters">
            <b class="popular__filters-caption filters__caption">Тип контента:</b>
            <ul class="popular__filters-list filters__list">
                <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                    <a class="filters__button filters__button--ellipse filters__button--all <?= !$post_type ? 'filters__button--active' : '' ?>" href="popular.php">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($content_types as $content_type): ?>
                  <li class="popular__filters-item filters__item">
                      <a class="filters__button filters__button--<?= $content_type['class_name'] ?> button <?= $post_type == $content_type[id] ? 'filters__button--active' : '' ?>" href="?post-type=<?= $content_type['id'] ?>">
                          <span class="visually-hidden"><?= $content_type['type_name'] ?></span>
                          <svg class="filters__icon" width="22" height="18">
                              <use xlink:href="#icon-filter-<?= $content_type['class_name'] ?>"></use>
                          </svg>
                      </a>
                  </li>
              <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="popular__posts">
        <?php foreach ($posts as $post_number => &$post):
          foreach ($post as &$note) {
              if (is_string($note)) {
                  $note = htmlspecialchars($note);
              }
          }
        ?>
        <article class="popular__post post post-<?= $post['class_name'] ?>">
            <header class="post__header">
                <h2>
                    <a href="post.php?id=<?=$post['id']?>"><?= $post['title'] ?></a>
                </h2>

            </header>
            <div class="post__main">
                <?php if ($post['class_name'] == "quote"): ?>
                <blockquote>
                    <?= crop_text($post['content']) ?>
                    <?php if ($post['quote_author']): ?>
                      <cite><?= $post['quote_author'] ?></cite>
                    <?php else: ?>
                      <cite>Неизвестный Автор</cite>
                    <?php endif; ?>
                </blockquote>

                <?php elseif ($post['class_name'] == "link"): ?>
                <div class="post-link__wrapper">
                    <a class="post-link__external" href="<?= $post['link'] ?>" title="Перейти по ссылке">
                        <div class="post-link__info-wrapper">
                            <div class="post-link__icon-wrapper">
                                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3><?= $post['title'] ?></h3>
                            </div>
                        </div>
                        <span><?= $post['content'] ?></span>
                    </a>
                </div>

                <?php elseif ($post['class_name'] == "photo"): ?>
                <div class="post-photo__image-wrapper">
                    <img src="<?= $post['image'] ?>" alt="Фото от пользователя" width="360" height="240">
                </div>

                <?php elseif ($post['class_name'] == "video"): ?>
                <div class="post-video__block">
                    <div class="post-video__preview">
                        <?=embed_youtube_cover($post['video']); ?>
                        <img src="img/coast-medium.jpg" alt="Превью к видео" width="360" height="188">
                    </div>
                    <a href="post-details.html" class="post-video__play-big button">
                        <svg class="post-video__play-big-icon" width="14" height="14">
                            <use xlink:href="#icon-video-play-big"></use>
                        </svg>
                        <span class="visually-hidden">Запустить проигрыватель</span>
                    </a>
                </div>

                <?php elseif ($post['class_name'] == "text"): ?>
                <?= crop_text($post['content']) ?>

                <?php endif; ?>
            </div>
            <footer class="post__footer">
                <div class="post__author">
                    <a class="post__author-link" href="profile.php?user=<?=$post['id']?>" title="Автор">
                        <div class="post__avatar-wrapper">
                            <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя">
                        </div>
                        <div class="post__info">
                            <b class="post__author-name"><?= $post['login'] ?></b>
                            <time class="post__time" datetime="<?= $post['date_add'] ?>" title="<?= date("Y-m-d H:i:s", strtotime($post['date_add'])) ?>"><?= get_post_interval($post['date_add'], 'назад') ?></time>
                        </div>
                    </a>
                </div>
                <div class="post__indicators">
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
                            <span>0</span>
                            <span class="visually-hidden">количество комментариев</span>
                        </a>
                    </div>
                </div>
            </footer>
        </article>
        <?php endforeach; ?>
    </div>
    <?php if($pages_count > 1): ?>
    <div class="popular__page-links">
        <?php if($current_page > 1): ?>
            <a class="popular__page-link popular__page-link--prev button button--gray" href="/popular.php?page=<?= $page_prev ?>">Предыдущая страница</a>
        <?php endif; ?>
        <?php if($current_page < $pages_count): ?>
            <a class="popular__page-link popular__page-link--next button button--gray" href="/popular.php?page=<?= $page_next ?>">Следующая страница</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<script src="js/custom.js"></script>
<script src="js/custom-sort.js"></script>
