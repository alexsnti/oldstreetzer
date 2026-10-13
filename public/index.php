<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli-server') {
    $file = __DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        return false;
    }
}

require dirname(__DIR__).'/src/bootstrap.php';
require dirname(__DIR__).'/src/Messenger.php';
require dirname(__DIR__).'/src/Social.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$path = rtrim($path, '/') ?: '/';

if ($method === 'POST') {
    csrf_check();
}

if ($path === '/' && $method === 'GET') {
    if (current_user()) {
        redirect('/fil');
    }
    render('landing');
}

if ($path === '/inscription' && $method === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';
    $ville = trim($_POST['ville'] ?? '');
    $vehicule = $_POST['vehicule'] ?? '';
    $marque = trim($_POST['marque'] ?? '');
    $naissance = trim($_POST['naissance'] ?? '');
    $sexe = $_POST['sexe'] ?? '';
    if ($nom === '' || $prenom === '' || $email === '' || $pseudo === '' || $ville === '') {
        flash('Nom, prénom, email, pseudo et ville sont requis.');
        redirect('/');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('Email invalide.');
        redirect('/');
    }
    if (strlen($pass) < 6 || $pass !== $pass2) {
        flash('Le mot de passe fait 6 caractères minimum, et les deux champs doivent être identiques.');
        redirect('/');
    }
    if (!in_array($vehicule, ['voiture', 'moto'], true)) {
        flash('Choisis une voiture ou une moto.');
        redirect('/');
    }
    try {
        db()->prepare(
            'INSERT INTO users (nom, prenom, email, pseudo, password_hash, ville, vehicule, sexe, marque, naissance, bio)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        )->execute([
            $nom, $prenom, $email, $pseudo, password_hash($pass, PASSWORD_DEFAULT),
            $ville, $vehicule, $sexe, $marque, $naissance, '',
        ]);
    } catch (PDOException $e) {
        flash('Cet email ou ce pseudo est déjà pris.');
        redirect('/');
    }
    $_SESSION['uid'] = (int) db()->lastInsertId();
    redirect('/fil');
}

if ($path === '/connexion' && $method === 'POST') {
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($_POST['password'] ?? '', $user['password_hash'])) {
        flash('Email ou mot de passe incorrect.');
        redirect('/');
    }
    session_regenerate_id(true);
    $_SESSION['uid'] = (int) $user['id'];
    redirect('/fil');
}

if ($path === '/deconnexion' && $method === 'POST') {
    $_SESSION = [];
    session_destroy();
    session_start();
    redirect('/');
}

if ($path === '/api/villes' && $method === 'GET') {
    $q = trim($_GET['q'] ?? '');
    if (mb_strlen($q) < 1) {
        json_out([]);
    }
    $stmt = db()->prepare('SELECT ville_nom_reel FROM villes_france WHERE ville_nom_reel LIKE ? ORDER BY ville_nom_reel LIMIT 8');
    $stmt->execute([$q.'%']);
    json_out($stmt->fetchAll());
}

if ($path === '/api/marques' && $method === 'GET') {
    $q = trim($_GET['q'] ?? '');
    $stmt = db()->prepare('SELECT nom FROM marques WHERE nom LIKE ? ORDER BY nom LIMIT 8');
    $stmt->execute([$q.'%']);
    json_out($stmt->fetchAll());
}

$user = require_user();
$uid = (int) $user['id'];

if ($path === '/fil' && $method === 'GET') {
    $posts = posts_where('1=1', [], $uid);
    render('feed', [
        'nav' => 'fil',
        'title' => 'Worldlane',
        'posts' => $posts,
        'commentsByPost' => comments_for(array_column($posts, 'id')),
        'composer' => true,
    ]);
}

if ($path === '/amis-fil' && $method === 'GET') {
    $ids = friend_ids($uid);
    $posts = $ids
        ? posts_where('p.user_id IN ('.implode(',', array_fill(0, count($ids), '?')).')', $ids, $uid)
        : [];
    render('feed', [
        'nav' => 'amis-fil',
        'title' => 'Friendslane',
        'posts' => $posts,
        'commentsByPost' => comments_for(array_column($posts, 'id')),
        'composer' => false,
        'empty' => 'Tes amis n\'ont encore rien posté.',
    ]);
}

if ($path === '/likes' && $method === 'GET') {
    $posts = posts_where('EXISTS(SELECT 1 FROM likes l WHERE l.post_id = p.id AND l.user_id = ?)', [$uid], $uid);
    render('feed', [
        'nav' => 'likes',
        'title' => 'Likes',
        'posts' => $posts,
        'commentsByPost' => comments_for(array_column($posts, 'id')),
        'composer' => false,
        'empty' => 'Tu n\'as pas encore liké de post.',
    ]);
}

if ($path === '/posts' && $method === 'POST') {
    try {
        $image = save_image($_FILES['image'] ?? []);
        $youtube = youtube_id(trim($_POST['youtube'] ?? ''));
        create_post($uid, $_POST['body'] ?? '', $image, $youtube);
    } catch (RuntimeException | InvalidArgumentException $e) {
        flash($e->getMessage());
    }
    redirect('/fil');
}

if (preg_match('#^/posts/(\d+)/like$#', $path, $m) && $method === 'POST') {
    toggle_like((int) $m[1], $uid);
    redirect(back_path());
}

if (preg_match('#^/posts/(\d+)/commentaire$#', $path, $m) && $method === 'POST') {
    add_comment((int) $m[1], $uid, $_POST['body'] ?? '');
    redirect(back_path());
}

if ($path === '/amis' && $method === 'GET') {
    $q = trim($_GET['q'] ?? '');
    render('people', [
        'nav' => 'amis',
        'friends' => friends_of($uid),
        'results' => $q !== '' ? search_users($q, $uid) : [],
        'q' => $q,
    ]);
}

if (preg_match('#^/profil/(\d+)$#', $path, $m) && $method === 'GET') {
    $profile = find_user((int) $m[1]);
    if (!$profile) {
        http_response_code(404);
        exit('Profil introuvable.');
    }
    $pid = (int) $profile['id'];
    $posts = posts_where('p.user_id = ?', [$pid], $uid);
    render('profile', [
        'nav' => '',
        'profile' => $profile,
        'posts' => $posts,
        'commentsByPost' => comments_for(array_column($posts, 'id')),
        'friends' => are_friends($uid, $pid),
    ]);
}

if (preg_match('#^/profil/(\d+)/ami$#', $path, $m) && $method === 'POST') {
    toggle_friend($uid, (int) $m[1]);
    redirect('/profil/'.$m[1]);
}

if ($path === '/messages' && $method === 'GET') {
    render('messages', ['nav' => 'messages', 'inbox' => inbox($uid), 'thread' => null]);
}

if ($path === '/messages' && $method === 'POST') {
    $other = (int) ($_POST['user_id'] ?? 0);
    $body = trim($_POST['body'] ?? '');
    if (!$other || $other === $uid || !find_user($other)) {
        flash('Choisis quelqu\'un.');
        redirect('/messages');
    }
    $cid = conversation_between($uid, $other);
    if ($body !== '') {
        send_message($cid, $uid, $body);
    } else {
        db()->prepare('UPDATE conversation_members SET hidden = 0 WHERE conversation_id = ? AND user_id = ?')
            ->execute([$cid, $uid]);
    }
    redirect('/messages/'.$cid);
}

if (preg_match('#^/messages/(\d+)$#', $path, $m) && $method === 'GET') {
    $cid = (int) $m[1];
    render('messages', [
        'nav' => 'messages',
        'inbox' => inbox($uid),
        'thread' => thread_messages($cid, $uid),
        'cid' => $cid,
        'other' => other_member($cid, $uid),
    ]);
}

if (preg_match('#^/messages/(\d+)$#', $path, $m) && $method === 'POST') {
    $cid = (int) $m[1];
    try {
        send_message($cid, $uid, $_POST['body'] ?? '');
    } catch (InvalidArgumentException $e) {
        flash($e->getMessage());
    }
    redirect('/messages/'.$cid);
}

if (preg_match('#^/messages/(\d+)/masquer$#', $path, $m) && $method === 'POST') {
    hide_conversation((int) $m[1], $uid);
    redirect('/messages');
}

if ($path === '/notifs' && $method === 'GET') {
    $rows = notifications_for($uid);
    mark_notifs_read($uid);
    render('notifs', ['nav' => 'notifs', 'rows' => $rows]);
}

if ($path === '/admin' && $method === 'GET') {
    if (!(int) $user['is_admin']) {
        http_response_code(403);
        exit('Réservé à l\'admin.');
    }
    render('admin', [
        'nav' => '',
        'users' => db()->query('SELECT id, prenom, nom, pseudo, email, is_admin FROM users ORDER BY id')->fetchAll(),
        'posts' => db()->query('SELECT p.id, p.body, u.pseudo FROM posts p JOIN users u ON u.id = p.user_id ORDER BY p.id DESC LIMIT 50')->fetchAll(),
    ]);
}

if (preg_match('#^/admin/users/(\d+)$#', $path, $m) && $method === 'POST') {
    if (!(int) $user['is_admin'] || (int) $m[1] === $uid) {
        http_response_code(403);
        exit('Interdit.');
    }
    db()->prepare('DELETE FROM users WHERE id = ?')->execute([(int) $m[1]]);
    redirect('/admin');
}

if (preg_match('#^/admin/posts/(\d+)$#', $path, $m) && $method === 'POST') {
    if (!(int) $user['is_admin']) {
        http_response_code(403);
        exit('Interdit.');
    }
    db()->prepare('DELETE FROM posts WHERE id = ?')->execute([(int) $m[1]]);
    redirect('/admin');
}

http_response_code(404);
exit('Page introuvable.');
