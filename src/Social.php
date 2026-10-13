<?php
declare(strict_types=1);

function friend_ids(int $uid): array
{
    $stmt = db()->prepare(
        'SELECT CASE WHEN requester_id = ? THEN addressee_id ELSE requester_id END AS fid
         FROM friendships WHERE requester_id = ? OR addressee_id = ?'
    );
    $stmt->execute([$uid, $uid, $uid]);
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

function are_friends(int $a, int $b): bool
{
    $stmt = db()->prepare(
        'SELECT 1 FROM friendships
         WHERE (requester_id = ? AND addressee_id = ?) OR (requester_id = ? AND addressee_id = ?)'
    );
    $stmt->execute([$a, $b, $b, $a]);
    return (bool) $stmt->fetchColumn();
}

function toggle_friend(int $me, int $other): void
{
    if ($me === $other) {
        return;
    }
    $pdo = db();
    if (are_friends($me, $other)) {
        $pdo->prepare(
            'DELETE FROM friendships
             WHERE (requester_id = ? AND addressee_id = ?) OR (requester_id = ? AND addressee_id = ?)'
        )->execute([$me, $other, $other, $me]);
        return;
    }
    $pdo->prepare('INSERT INTO friendships (requester_id, addressee_id) VALUES (?, ?)')->execute([$me, $other]);
    notify($other, $me, 'friend', null);
}

function notify(int $userId, int $actorId, string $type, ?int $postId): void
{
    if ($userId === $actorId) {
        return;
    }
    db()->prepare('INSERT INTO notifications (user_id, actor_id, type, post_id) VALUES (?, ?, ?, ?)')
        ->execute([$userId, $actorId, $type, $postId]);
}

function unread_notifs(int $uid): int
{
    $stmt = db()->prepare('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND seen = 0');
    $stmt->execute([$uid]);
    return (int) $stmt->fetchColumn();
}

function notifications_for(int $uid): array
{
    $stmt = db()->prepare(
        'SELECT n.*, u.pseudo, u.prenom, u.nom, u.avatar
         FROM notifications n JOIN users u ON u.id = n.actor_id
         WHERE n.user_id = ? ORDER BY n.id DESC LIMIT 40'
    );
    $stmt->execute([$uid]);
    return $stmt->fetchAll();
}

function mark_notifs_read(int $uid): void
{
    db()->prepare('UPDATE notifications SET seen = 1 WHERE user_id = ?')->execute([$uid]);
}

function posts_where(string $where, array $params, int $me): array
{
    $sql = 'SELECT p.*, u.pseudo, u.prenom, u.nom, u.avatar,
              (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS likes,
              (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.id) AS comment_count,
              EXISTS(SELECT 1 FROM likes l WHERE l.post_id = p.id AND l.user_id = ?) AS liked
            FROM posts p JOIN users u ON u.id = p.user_id
            WHERE '.$where.'
            ORDER BY p.id DESC LIMIT 40';
    $stmt = db()->prepare($sql);
    $stmt->execute(array_merge([$me], $params));
    return $stmt->fetchAll();
}

function comments_for(array $postIds): array
{
    if (!$postIds) {
        return [];
    }
    $in = implode(',', array_fill(0, count($postIds), '?'));
    $stmt = db()->prepare(
        "SELECT c.*, u.pseudo FROM comments c JOIN users u ON u.id = c.user_id
         WHERE c.post_id IN ($in) ORDER BY c.id ASC"
    );
    $stmt->execute($postIds);
    $grouped = [];
    foreach ($stmt->fetchAll() as $row) {
        $grouped[$row['post_id']][] = $row;
    }
    return $grouped;
}

function find_post(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function find_user(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function toggle_like(int $postId, int $uid): void
{
    $post = find_post($postId);
    if (!$post) {
        return;
    }
    $pdo = db();
    $exists = $pdo->prepare('SELECT 1 FROM likes WHERE user_id = ? AND post_id = ?');
    $exists->execute([$uid, $postId]);
    if ($exists->fetchColumn()) {
        $pdo->prepare('DELETE FROM likes WHERE user_id = ? AND post_id = ?')->execute([$uid, $postId]);
        return;
    }
    $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (?, ?)')->execute([$uid, $postId]);
    notify((int) $post['user_id'], $uid, 'like', $postId);
}

function add_comment(int $postId, int $uid, string $body): void
{
    $post = find_post($postId);
    $body = trim($body);
    if (!$post || $body === '') {
        return;
    }
    db()->prepare('INSERT INTO comments (post_id, user_id, body) VALUES (?, ?, ?)')->execute([$postId, $uid, $body]);
    notify((int) $post['user_id'], $uid, 'comment', $postId);
}

function create_post(int $uid, string $body, ?string $image, ?string $youtube): void
{
    $body = trim($body);
    if ($body === '' && !$image && !$youtube) {
        throw new InvalidArgumentException('Écris quelque chose, ou ajoute une photo.');
    }
    db()->prepare('INSERT INTO posts (user_id, body, image_path, youtube_id) VALUES (?, ?, ?, ?)')
        ->execute([$uid, $body, $image, $youtube]);
}

function search_users(string $q, int $me): array
{
    $like = '%'.$q.'%';
    $stmt = db()->prepare(
        'SELECT id, prenom, nom, pseudo, avatar, ville, vehicule, marque
         FROM users WHERE id != ? AND (pseudo LIKE ? OR prenom LIKE ? OR nom LIKE ?)
         ORDER BY pseudo LIMIT 20'
    );
    $stmt->execute([$me, $like, $like, $like]);
    return $stmt->fetchAll();
}

function friends_of(int $uid): array
{
    $ids = friend_ids($uid);
    if (!$ids) {
        return [];
    }
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("SELECT id, prenom, nom, pseudo, avatar, ville, marque, vehicule FROM users WHERE id IN ($in) ORDER BY pseudo");
    $stmt->execute($ids);
    return $stmt->fetchAll();
}
