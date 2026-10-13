<h1>Admin</h1>
<h2>Comptes</h2>
<table>
  <tr><th>Pseudo</th><th>Email</th><th></th></tr>
  <?php foreach ($users as $u): ?>
    <tr>
      <td><a href="/profil/<?= (int) $u['id'] ?>"><?= e($u['pseudo']) ?></a></td>
      <td><?= e($u['email']) ?></td>
      <td>
        <?php if (!(int) $u['is_admin']): ?>
          <form method="post" action="/admin/users/<?= (int) $u['id'] ?>"><?= csrf_field() ?><button type="submit">Supprimer</button></form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<h2>Posts</h2>
<table>
  <?php foreach ($posts as $p): ?>
    <tr>
      <td><?= e($p['pseudo']) ?></td>
      <td><?= e(mb_strimwidth($p['body'], 0, 80, '…')) ?></td>
      <td><form method="post" action="/admin/posts/<?= (int) $p['id'] ?>"><?= csrf_field() ?><button type="submit">Supprimer</button></form></td>
    </tr>
  <?php endforeach; ?>
</table>
