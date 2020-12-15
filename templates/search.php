<?php
print($search2);
?>
<h1 class="visually-hidden">Страница результатов поиска</h1>
      <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
          <div class="search__query container">
            <span>Вы искали:</span>
            <span class="search__query-text"><?= $search_tags ?></span>
          </div>
        </div>
        <div class="search__results-wrapper">
          <div class="container">
            <div class="search__content">
            <?php if (count($search_posts)): ?>
            <?php foreach ($search_posts as $post_number => $post): ?>
              <article class="search__post post post-<?= $post['class_name'] ?>">
                <header class="post__header post__author">
                  <a class="post__author-link" href="#" title="Автор">
                    <div class="post__avatar-wrapper">
                      <img class="post__author-avatar" src="<?= $post['avatar'] ?>" alt="Аватар пользователя" width="60" height="60">
                    </div>
                    <div class="post__info">
                      <b class="post__author-name"><?= $post['login'] ?></b>
                      <span class="post__time"><?= get_post_interval($post['date_add'], 'назад') ?></span>
                    </div>
                  </a>
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
                <footer class="post__footer post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
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
                      <span><?= $post['comments_count'] ?></span>
                      <span class="visually-hidden">количество комментариев</span>
                    </a>
                  </div>
                </footer>
              </article>
              <?php endforeach; ?>
            <?php endif; ?>

            <?php if(!count($search_posts)): ?>
              <div class="search__results-wrapper">
                  <div class="search__no-results container">
                    <p class="search__no-results-info">К сожалению, ничего не найдено.</p>
                    <p class="search__no-results-desc">
                      Попробуйте изменить поисковый запрос или просто зайти в раздел &laquo;Популярное&raquo;, там живет самый крутой контент.
                    </p>
                    <div class="search__links">
                      <a class="search__popular-link button button--main" href="#">Популярное</a>
                      <a class="search__back-link" href="#">Вернуться назад</a>
                    </div>
                  </div>
                </div>
            <?php endif; ?>
            
            </div>
          </div>
        </div>
      </section>