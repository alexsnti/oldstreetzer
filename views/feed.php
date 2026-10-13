<h1><?= e($title) ?></h1>
<?php if (!empty($composer)): ?>
  <form class="composer" method="post" action="/posts" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <textarea name="body" rows="3" placeholder="Entrez votre message..."></textarea>
    <div class="row">
      <input name="image" type="file" accept="image/*">
      <input name="youtube" placeholder="Lien YouTube">
      <button type="submit">Publier</button>
    </div>
  </form>
<?php endif; ?>
<?php if (!$posts): ?>
  <p class="empty"><?= e($empty ?? 'Aucun post à afficher.') ?></p>
<?php endif; ?>
<?php foreach ($posts as $post):
  $comments = $commentsByPost[$post['id']] ?? [];
  $back = $_SERVER['REQUEST_URI'] ?? '/fil';
  require __DIR__.'/post.php';
endforeach; ?>
