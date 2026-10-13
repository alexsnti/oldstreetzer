<h1>Notifs</h1>
<?php if (!$rows): ?><p class="empty">Rien pour le moment.</p><?php endif; ?>
<ul class="notifs">
  <?php foreach ($rows as $n): ?>
    <li>
      <?php
        $who = e($n['prenom'].' '.$n['nom']);
        if ($n['type'] === 'like') {
            echo $who.' a liké ton post.';
        } elseif ($n['type'] === 'comment') {
            echo $who.' a commenté ton post.';
        } else {
            echo $who.' t\'a ajouté en ami.';
        }
      ?>
      <?php if ($n['post_id']): ?> <a href="/fil#post-<?= (int) $n['post_id'] ?>">Voir</a><?php endif; ?>
      <small><?= e(quand($n['created_at'])) ?></small>
    </li>
  <?php endforeach; ?>
</ul>
