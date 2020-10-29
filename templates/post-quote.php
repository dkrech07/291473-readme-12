<div class="post-details__image-wrapper post-quote">
    <div class="post__main">
      <blockquote>
        <p>
          <?= $post['content'] ?>
        </p>
        <?php if ($post['quote_author']): ?>
          <cite><?= $post['quote_author'] ?></cite>
        <?php else: ?>
          <cite>Неизвестный Автор</cite>
        <?php endif; ?>
      </blockquote>
    </div>
</div>
