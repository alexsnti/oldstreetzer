<h1>Friends</h1>
<form class="row" method="get" action="/amis">
  <input name="q" value="<?= e($q) ?>" placeholder="Chercher un pseudo, un prénom">
  <button type="submit">Chercher</button>
</form>
<?php if ($q !== ''): ?>
  <h2>Résultats</h2>
  <ul class="people">
    <?php foreach ($results as $p): ?>
      <li><a href="/profil/<?= (int) $p['id'] ?>"><?= e($p['prenom'].' '.$p['nom']) ?></a> <span>@<?= e($p['pseudo']) ?></span></li>
    <?php endforeach; ?>
    <?php if (!$results): ?><li>Personne.</li><?php endif; ?>
  </ul>
<?php endif; ?>
<h2>Tes amis</h2>
<?php if (!$friends): ?><p class="empty">Tu ne suis encore personne.</p><?php endif; ?>
<ul class="people">
  <?php foreach ($friends as $p): ?>
    <li>
      <img src="/<?= e($p['avatar'] !== '' ? $p['avatar'] : 'media/pp-alex.jpg') ?>" alt="">
      <a href="/profil/<?= (int) $p['id'] ?>"><?= e($p['prenom'].' '.$p['nom']) ?></a>
      <span><?= e($p['marque']) ?></span>
    </li>
  <?php endforeach; ?>
</ul>
