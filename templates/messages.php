<h1 class="visually-hidden">Личные сообщения</h1>
      <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
          <ul class="messages__contacts-list tabs__list">
            <?php foreach($chats as $chat_number => $chat): ?>
            <li class="messages__contacts-item messages__contacts-item--new">
              <a class="messages__contacts-tab tabs__item <?= $recipient_id == $chat['chat_recipient_id'] ? 'messages__contacts-tab--active tabs__item--active' : ''?>" href="#">
                <div class="messages__avatar-wrapper">
                  <img class="messages__avatar" src="<?= $chat['avatar'] ?>" alt="Аватар пользователя">
                  <i class="messages__indicator">2</i>
                </div>
                <div class="messages__info">
                  <span class="messages__contact-name">
                    <?= $chat['login'] ?>
                  </span>
                  <div class="messages__preview">
                    <p class="messages__preview-text">
                      Ок, бро! По рукам
                    </p>
                    <time class="messages__preview-time" datetime="2019-05-01T00:15">
                      00:15
                    </time>
                  </div>
                </div>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="messages__chat">
          <div class="messages__chat-wrapper">
            <ul class="messages__list tabs__content tabs__content--active">
            <?php foreach($chat_messages as $chat_message_number => $chat_message): ?>
              <li class="messages__item">
                <div class="messages__info-wrapper">
                  <div class="messages__item-avatar">
                    <a class="messages__author-link" href="#">
                      <img class="messages__avatar" src="<?= $sender_avatars[$chat_message_number] ?>" alt="Аватар пользователя">
                    </a>
                  </div>
                  <div class="messages__item-info">
                    <a class="messages__author" href="#">
                      <?= $chat_message['login'] ?>
                    </a>
                    <time class="messages__time" datetime="2019-05-01T14:40">
                      <?= get_post_interval($chat_message['date_add'], 'назад') ?>
                    </time>
                  </div>
                </div>
                <p class="messages__text">
                  <?= $chat_message['content'] ?>
                </p>
              </li>
            <?php endforeach; ?>
            </ul>
          </div>
          <div class="comments">
            <form class="comments__form form" action="messages.php<?= $recipient_id ? '?user=' . $recipient_id  : '' ?>" method="post">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="<?=$avatar?>" alt="Аватар пользователя">
              </div>
              <div class="form__input-section form__input-section--error">
                <textarea class="comments__textarea form__textarea form__input"
                id="chat-message" name="chat-message" placeholder="Ваше сообщение"></textarea>
                <label class="visually-hidden">Ваше сообщение</label>
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                  <p class="form__error-desc">Это поле обязательно к заполнению</p>
                </div>
              </div>
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
          </div>
        </div>
      </section>
