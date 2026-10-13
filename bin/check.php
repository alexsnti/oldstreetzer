<?php
declare(strict_types=1);

require dirname(__DIR__).'/src/bootstrap.php';
require dirname(__DIR__).'/src/Messenger.php';

function check(bool $ok, string $msg): void
{
    if (!$ok) {
        fwrite(STDERR, "FAIL $msg\n");
        throw new RuntimeException($msg);
    }
    echo "ok $msg\n";
}

$pdo = db();
$suffix = bin2hex(random_bytes(4));
$hash = password_hash('x', PASSWORD_DEFAULT);
$ins = $pdo->prepare(
    'INSERT INTO users (nom, prenom, email, pseudo, password_hash, bio) VALUES (?, ?, ?, ?, ?, ?)'
);
$ins->execute(['A', 'A', "a-$suffix@test", "a$suffix", $hash, '']);
$a = (int) $pdo->lastInsertId();
$ins->execute(['B', 'B', "b-$suffix@test", "b$suffix", $hash, '']);
$b = (int) $pdo->lastInsertId();

try {
    $cid = conversation_between($a, $b);
    check(conversation_between($a, $b) === $cid, 'une seule conversation pour le duo');

    $m1 = send_message($cid, $a, 'salut');
    check(unread_total($b) === 1, 'le destinataire a un non-lu');
    check(unread_total($a) === 0, 'l\'expéditeur n\'a pas de non-lu');

    hide_conversation($cid, $b);
    check(inbox($b) === [], 'masquée chez B');
    check(count(inbox($a)) === 1, 'toujours visible chez A');

    send_message($cid, $a, 'tu es là ?');
    check(count(inbox($b)) === 1, 'un nouveau message rouvre la boîte de B');
    check(unread_total($b) === 2, 'les deux messages sont non lus');

    thread_messages($cid, $b);
    check(unread_total($b) === 0, 'ouvrir le fil marque lu');

    hide_conversation($cid, $a);
    hide_conversation($cid, $b);
    $left = $pdo->prepare('SELECT COUNT(*) FROM conversations WHERE id = ?');
    $left->execute([$cid]);
    check((int) $left->fetchColumn() === 0, 'masquée des deux côtés, la conversation disparaît');
} finally {
    $pdo->prepare('DELETE FROM users WHERE id IN (?, ?)')->execute([$a, $b]);
}

echo "check ok\n";
