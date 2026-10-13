<h1>Messages</h1>
<div class="inbox">
  <div>
    <?php if (!$inbox): ?><p class="empty">Aucune conversation.</p><?php endif; ?>
    <ul class="threads">
      <?php foreach ($inbox as $row): ?>
        <li class="<?= !empty($cid) && (int) $cid === (int) $row['id'] ? 'on' : '' ?>">
          <a href="/messages/<?= (int) $row['id'] ?>">
            <?= e($row['prenom'].' '.$row['nom']) ?>
            <?php if ((int) $row['unread'] > 0): ?><b><?= (int) $row['unread'] ?></b><?php endif; ?>
            <small><?= e($row['last_body'] ?? '') ?></small>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="thread">
    <?php if ($thread === null): ?>
      <p class="empty">Choisis une conversation, ou écris depuis un profil.</p>
    <?php elseif (!$other): ?>
      <p class="empty">Cette conversation n'a plus d'interlocuteur.</p>
    <?php else: ?>
      <header class="row">
        <h2><a href="/profil/<?= (int) $other['id'] ?>"><?= e($other['prenom'].' '.$other['nom']) ?></a></h2>
        <form method="post" action="/messages/<?= (int) $cid ?>/masquer">
          <?= csrf_field() ?>
          <button class="ghost" type="submit">Masquer</button>
        </form>
      </header>
      <ol class="bubbles">
        <?php foreach ($thread as $msg): ?>
          <li class="<?= (int) $msg['user_id'] === (int) $user['id'] ? 'mine' : 'theirs' ?>">
            <span><?= e($msg['pseudo']) ?> · <?= e(quand($msg['created_at'])) ?></span>
            <p><?= e($msg['body']) ?></p>
          </li>
        <?php endforeach; ?>
      </ol>
      <form method="post" action="/messages/<?= (int) $cid ?>" class="row">
        <?= csrf_field() ?>
        <input name="body" placeholder="Écris ton message et envoie" required autofocus>
        <button type="submit">Envoyer</button>
      </form>
    <?php endif; ?>
  </div>
</div>
