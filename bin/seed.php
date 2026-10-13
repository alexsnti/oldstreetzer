<?php
declare(strict_types=1);

require dirname(__DIR__).'/src/bootstrap.php';
require dirname(__DIR__).'/src/Messenger.php';
require dirname(__DIR__).'/src/Social.php';

$pdo = db();
$hash = password_hash('streetzer', PASSWORD_DEFAULT);

$insert = $pdo->prepare(
    'INSERT INTO users (nom, prenom, email, pseudo, password_hash, ville, vehicule, sexe, marque, naissance, bio, avatar, is_admin)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$insert->execute(['Martin', 'Alex', 'alex@streetzer.local', 'alex', $hash, 'Lyon', 'voiture', 'male', 'Renault Clio', '12/04/1990', 'Toujours sur la route.', 'media/pp-alex.jpg', 1]);
$alex = (int) $pdo->lastInsertId();
$insert->execute(['Bernard', 'Lea', 'lea@streetzer.local', 'lea', $hash, 'Marseille', 'moto', 'female', 'Yamaha MT-07', '03/09/1992', 'Virages et soleil.', 'media/pp-lea.jpg', 0]);
$lea = (int) $pdo->lastInsertId();

$post = $pdo->prepare('INSERT INTO posts (user_id, body, image_path) VALUES (?, ?, ?)');
$post->execute([$alex, 'Première sortie avec la Clio, le col était vide.', 'media/route.jpg']);
$p1 = (int) $pdo->lastInsertId();
$post->execute([$lea, 'MT-07 au lever du soleil, Marseille est à nous.', 'media/home.jpg']);
$p2 = (int) $pdo->lastInsertId();
$post->execute([$alex, 'Soirée parking, qui vient ?', 'media/home2.jpg']);
$post->execute([$lea, 'Plein fait, prochaine étape la nationale.', 'media/home3.jpg']);

$pdo->prepare('INSERT INTO comments (post_id, user_id, body) VALUES (?, ?, ?)')->execute([$p1, $lea, 'Joli col. Tu étais à quelle heure ?']);
$pdo->prepare('INSERT INTO comments (post_id, user_id, body) VALUES (?, ?, ?)')->execute([$p2, $alex, 'La lumière est folle. On se croise ce week-end ?']);
$pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (?, ?)')->execute([$alex, $p2]);
$pdo->prepare('INSERT INTO friendships (requester_id, addressee_id) VALUES (?, ?)')->execute([$alex, $lea]);
$pdo->prepare('INSERT INTO notifications (user_id, actor_id, type, post_id, seen) VALUES (?, ?, ?, ?, 0)')
    ->execute([$alex, $lea, 'comment', $p1]);

$cid = conversation_between($alex, $lea);
send_message($cid, $lea, 'Salut Alex, tu roules toujours en Clio ?');
send_message($cid, $alex, 'Oui, et toi la MT tient le rythme.');
// Léa n'a pas encore lu la réponse d'Alex.
$pdo->prepare('UPDATE conversation_members SET last_read_id = 1 WHERE conversation_id = ? AND user_id = ?')
    ->execute([$cid, $lea]);

echo "seed ok alex=$alex lea=$lea conversation=$cid\n";
