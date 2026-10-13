<article class="post" id="post-<?= (int) $post['id'] ?>">
  <?php if (!empty($post['image_path'])): ?>
    <img class="shot" src="/<?= e($post['image_path']) ?>" alt="">
  <?php endif; ?>
  <?php if (!empty($post['youtube_id'])): ?>
    <div class="video"><iframe src="https://www.youtube.com/embed/<?= e($post['youtube_id']) ?>" title="YouTube" allowfullscreen></iframe></div>
  <?php endif; ?>
  <div class="post-body">
    <p class="meta"><a href="/profil/<?= (int) $post['user_id'] ?>"><?= e($post['pseudo']) ?></a> · <?= e(quand($post['created_at'])) ?></p>
    <?php if ($post['body'] !== ''): ?><p><?= nl2br(e($post['body'])) ?></p><?php endif; ?>
    <form method="post" action="/posts/<?= (int) $post['id'] ?>/like" class="row">
      <?= csrf_field() ?>
      <input type="hidden" name="back" value="<?= e($back) ?>">
      <button type="submit" class="<?= $post['liked'] ? 'liked' : '' ?>"><?= $post['liked'] ? 'Liké' : 'Like' ?> · <?= (int) $post['likes'] ?></button>
    </form>
    <ul class="comments">
      <?php foreach ($comments as $c): ?>
        <li><strong><?= e($c['pseudo']) ?></strong> <?= e($c['body']) ?></li>
      <?php endforeach; ?>
    </ul>
    <form method="post" action="/posts/<?= (int) $post['id'] ?>/commentaire" class="row">
      <?= csrf_field() ?>
      <input type="hidden" name="back" value="<?= e($back) ?>">
      <input name="body" placeholder="Commentaire" required>
      <button type="submit">Envoyer</button>
    </form>
  </div>
</article>
