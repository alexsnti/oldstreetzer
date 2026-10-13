<?php
$flash = flash();
$title = $title ?? 'Streetzer';
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> · Streetzer</title>
  <link rel="icon" href="/favicon.ico">
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body class="<?= $bare ? 'bare' : 'app' ?>">
<?php if ($bare): ?>
  <?php require $viewFile; ?>
<?php else: ?>
  <div class="shell">
    <aside class="side">
      <a class="brand" href="/fil"><img src="/assets/logo.png" alt="Streetzer"></a>
      <p class="who"><a href="/profil/<?= (int) $user['id'] ?>"><?= e($user['pseudo']) ?></a><span><?= e($user['prenom'].' '.$user['nom']) ?></span></p>
      <nav>
        <a class="<?= ($nav ?? '') === 'fil' ? 'on' : '' ?>" href="/fil">Worldlane</a>
        <a class="<?= ($nav ?? '') === 'amis-fil' ? 'on' : '' ?>" href="/amis-fil">Friendslane</a>
        <a class="<?= ($nav ?? '') === 'likes' ? 'on' : '' ?>" href="/likes">Likes</a>
        <a class="<?= ($nav ?? '') === 'amis' ? 'on' : '' ?>" href="/amis">Friends</a>
        <a class="<?= ($nav ?? '') === 'messages' ? 'on' : '' ?>" href="/messages">Messages<?php if (!empty($unreadMessages)): ?> <b><?= (int) $unreadMessages ?></b><?php endif; ?></a>
        <a class="<?= ($nav ?? '') === 'notifs' ? 'on' : '' ?>" href="/notifs">Notifs<?php if (!empty($unreadNotifs)): ?> <b><?= (int) $unreadNotifs ?></b><?php endif; ?></a>
        <?php if (!empty($user['is_admin'])): ?><a href="/admin">Admin</a><?php endif; ?>
      </nav>
      <form method="post" action="/deconnexion"><?= csrf_field() ?><button class="ghost" type="submit">Logout</button></form>
    </aside>
    <main>
      <?php if ($flash): ?><p class="flash"><?= e($flash) ?></p><?php endif; ?>
      <?php require $viewFile; ?>
    </main>
  </div>
<?php endif; ?>
<script src="/assets/app.js"></script>
</body>
</html>
