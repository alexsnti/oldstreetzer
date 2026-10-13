<?php $back = '/profil/'.(int) $profile['id']; ?>
<header class="profile">
  <img class="avatar" src="/<?= e($profile['avatar'] !== '' ? $profile['avatar'] : 'media/pp-alex.jpg') ?>" alt="">
  <div>
    <h1><?= e($profile['prenom'].' '.$profile['nom']) ?></h1>
    <p class="meta">@<?= e($profile['pseudo']) ?> · <?= e($profile['ville']) ?></p>
    <p><?= e($profile['vehicule']) ?> <?= e($profile['marque']) ?></p>
    <?php if ($profile['bio'] !== ''): ?><p><?= e($profile['bio']) ?></p><?php endif; ?>
    <?php if ((int) $profile['id'] !== (int) $user['id']): ?>
      <form method="post" action="/profil/<?= (int) $profile['id'] ?>/ami" class="row">
        <?= csrf_field() ?>
        <button type="submit"><?= $friends ? 'Retirer des amis' : 'Ajouter en ami' ?></button>
      </form>
      <form method="post" action="/messages" class="row">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" value="<?= (int) $profile['id'] ?>">
        <input name="body" placeholder="Écrire un message">
        <button type="submit">Envoyer</button>
      </form>
    <?php endif; ?>
  </div>
</header>
<?php foreach ($posts as $post):
  $comments = $commentsByPost[$post['id']] ?? [];
  require __DIR__.'/post.php';
endforeach; ?>
<?php if (!$posts): ?><p class="empty">Aucun post.</p><?php endif; ?>
