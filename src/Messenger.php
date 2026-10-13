<?php
declare(strict_types=1);

function conversation_between(int $a, int $b): int
{
    $stmt = db()->prepare(
        'SELECT c.id FROM conversations c
         JOIN conversation_members ma ON ma.conversation_id = c.id AND ma.user_id = ?
         JOIN conversation_members mb ON mb.conversation_id = c.id AND mb.user_id = ?'
    );
    $stmt->execute([$a, $b]);
    $id = $stmt->fetchColumn();
    if ($id) {
        return (int) $id;
    }
    $pdo = db();
    $pdo->prepare('INSERT INTO conversations (created_at) VALUES (NOW())')->execute();
    $cid = (int) $pdo->lastInsertId();
    $add = $pdo->prepare('INSERT INTO conversation_members (conversation_id, user_id) VALUES (?, ?)');
    $add->execute([$cid, $a]);
    $add->execute([$cid, $b]);
    return $cid;
}

function assert_member(int $cid, int $uid): void
{
    $stmt = db()->prepare('SELECT 1 FROM conversation_members WHERE conversation_id = ? AND user_id = ?');
    $stmt->execute([$cid, $uid]);
    if (!$stmt->fetchColumn()) {
        http_response_code(404);
        exit('Conversation introuvable.');
    }
}

function send_message(int $cid, int $uid, string $body): int
{
    assert_member($cid, $uid);
    $body = trim($body);
    if ($body === '') {
        throw new InvalidArgumentException('Message vide.');
    }
    $pdo = db();
    $pdo->prepare('INSERT INTO messages (conversation_id, user_id, body) VALUES (?, ?, ?)')
        ->execute([$cid, $uid, $body]);
    $mid = (int) $pdo->lastInsertId();
    // Un nouveau message rouvre la conversation des deux côtés.
    $pdo->prepare('UPDATE conversation_members SET hidden = 0 WHERE conversation_id = ?')->execute([$cid]);
    $pdo->prepare('UPDATE conversation_members SET last_read_id = ? WHERE conversation_id = ? AND user_id = ?')
        ->execute([$mid, $cid, $uid]);
    return $mid;
}

function hide_conversation(int $cid, int $uid): void
{
    assert_member($cid, $uid);
    $pdo = db();
    $pdo->prepare('UPDATE conversation_members SET hidden = 1 WHERE conversation_id = ? AND user_id = ?')
        ->execute([$cid, $uid]);
    $left = $pdo->prepare('SELECT COUNT(*) FROM conversation_members WHERE conversation_id = ? AND hidden = 0');
    $left->execute([$cid]);
    if ((int) $left->fetchColumn() === 0) {
        $pdo->prepare('DELETE FROM conversations WHERE id = ?')->execute([$cid]);
    }
}

function mark_read(int $cid, int $uid): void
{
    assert_member($cid, $uid);
    db()->prepare(
        'UPDATE conversation_members
         SET last_read_id = (SELECT COALESCE(MAX(id), 0) FROM messages WHERE conversation_id = ?)
         WHERE conversation_id = ? AND user_id = ?'
    )->execute([$cid, $cid, $uid]);
}

function thread_messages(int $cid, int $uid): array
{
    assert_member($cid, $uid);
    mark_read($cid, $uid);
    $stmt = db()->prepare(
        'SELECT m.*, u.pseudo, u.prenom, u.nom
         FROM messages m JOIN users u ON u.id = m.user_id
         WHERE m.conversation_id = ? ORDER BY m.id ASC'
    );
    $stmt->execute([$cid]);
    return $stmt->fetchAll();
}

function inbox(int $uid): array
{
    $stmt = db()->prepare(
        'SELECT c.id,
                other.id AS other_id, other.pseudo, other.prenom, other.nom, other.avatar,
                (SELECT body FROM messages WHERE conversation_id = c.id ORDER BY id DESC LIMIT 1) AS last_body,
                (SELECT COUNT(*) FROM messages m
                   WHERE m.conversation_id = c.id AND m.user_id != me.user_id AND m.id > me.last_read_id) AS unread
         FROM conversation_members me
         JOIN conversations c ON c.id = me.conversation_id
         JOIN conversation_members them ON them.conversation_id = c.id AND them.user_id != me.user_id
         JOIN users other ON other.id = them.user_id
         WHERE me.user_id = ? AND me.hidden = 0
         ORDER BY c.id DESC'
    );
    $stmt->execute([$uid]);
    return $stmt->fetchAll();
}

function unread_total(int $uid): int
{
    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM messages m
         JOIN conversation_members me ON me.conversation_id = m.conversation_id AND me.user_id = ?
         WHERE me.hidden = 0 AND m.user_id != ? AND m.id > me.last_read_id'
    );
    $stmt->execute([$uid, $uid]);
    return (int) $stmt->fetchColumn();
}

function other_member(int $cid, int $uid): ?array
{
    $stmt = db()->prepare(
        'SELECT u.* FROM conversation_members cm JOIN users u ON u.id = cm.user_id
         WHERE cm.conversation_id = ? AND cm.user_id != ?'
    );
    $stmt->execute([$cid, $uid]);
    $row = $stmt->fetch();
    return $row ?: null;
}
