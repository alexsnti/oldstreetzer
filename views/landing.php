<div class="landing">
  <header class="top">
    <img src="/assets/logo.png" alt="Streetzer">
    <form method="post" action="/connexion" class="login">
      <?= csrf_field() ?>
      <input name="email" type="email" placeholder="Email" required autocomplete="username">
      <input name="password" type="password" placeholder="Mot de passe" required autocomplete="current-password">
      <button type="submit">Connexion</button>
    </form>
  </header>
  <section class="hero">
    <form method="post" action="/inscription" class="card">
      <?= csrf_field() ?>
      <h1>Inscription</h1>
      <?php if (!empty($flash)): ?><p class="flash"><?= e($flash) ?></p><?php endif; ?>
      <div class="grid">
        <input name="nom" placeholder="Nom" required>
        <input name="prenom" placeholder="Prénom" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="pseudo" placeholder="Pseudo" required autocomplete="off">
        <input name="password" type="password" placeholder="Mot de passe (6 min)" required autocomplete="new-password">
        <input name="password2" type="password" placeholder="Confirmation" required autocomplete="new-password">
        <input name="ville" placeholder="Ville" required list="villes" autocomplete="off">
        <datalist id="villes"></datalist>
        <input name="naissance" placeholder="Date de naissance">
        <select name="vehicule" required>
          <option value="">Type de véhicule</option>
          <option value="voiture">Voiture</option>
          <option value="moto">Moto</option>
        </select>
        <input name="marque" placeholder="Marque" list="marques" autocomplete="off">
        <datalist id="marques"></datalist>
        <select name="sexe">
          <option value="">Sexe</option>
          <option value="male">Homme</option>
          <option value="female">Femme</option>
        </select>
      </div>
      <button type="submit">S'inscrire</button>
      <p class="hint">Démo : alex@streetzer.local ou lea@streetzer.local, mot de passe streetzer.</p>
    </form>
  </section>
</div>
