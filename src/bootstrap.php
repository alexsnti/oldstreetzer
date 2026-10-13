<?php
declare(strict_types=1);

const DB_DSN = 'mysql:host=127.0.0.1;dbname=streetzer;charset=utf8mb4';
const DB_USER = 'streetzer';
const DB_PASS = 'streetzer';

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): never
{
    header('Location: '.$to);
    exit;
}

function flash(?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'] = $value;
        return null;
    }
    $saved = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $saved;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">';
}

function csrf_check(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Session expirée, recharge la page.');
    }
}

function current_user(): ?array
{
    $id = $_SESSION['uid'] ?? null;
    if (!$id) {
        return null;
    }
    $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([(int) $id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) {
        flash('Connecte-toi pour continuer.');
        redirect('/');
    }
    return $user;
}

function back_path(): string
{
    $back = $_POST['back'] ?? '/fil';
    if (!is_string($back) || $back === '' || $back[0] !== '/' || str_starts_with($back, '//')) {
        return '/fil';
    }
    return $back;
}

function quand(string $dt): string
{
    $t = strtotime($dt);
    return $t ? date('d/m H:i', $t) : $dt;
}

function youtube_id(string $url): ?string
{
    if (preg_match('~(?:youtu\.be/|v=|embed/)([\w-]{11})~', $url, $m)) {
        return $m[1];
    }
    return null;
}

function save_image(array $file): ?string
{
    $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Envoi de l\'image impossible.');
    }
    $info = @getimagesize($file['tmp_name']);
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($allowed[$info[2]])) {
        throw new RuntimeException('Le fichier doit être une image.');
    }
    $name = bin2hex(random_bytes(8)).'.'.$allowed[$info[2]];
    $dest = dirname(__DIR__).'/public/uploads/'.$name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Envoi de l\'image impossible.');
    }
    return 'uploads/'.$name;
}

function render(string $view, array $data = []): never
{
    extract($data, EXTR_SKIP);
    $user = current_user();
    $viewFile = dirname(__DIR__).'/views/'.$view.'.php';
    $bare = $view === 'landing';
    if ($user && !$bare) {
        $unreadMessages = unread_total((int) $user['id']);
        $unreadNotifs = unread_notifs((int) $user['id']);
    }
    require dirname(__DIR__).'/views/layout.php';
    exit;
}

function json_out(array $data): never
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (PHP_SAPI !== 'cli') {
    session_start();
}
