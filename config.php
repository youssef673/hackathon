<?php
session_start();

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'doveri_ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'Doveri Shop');

date_default_timezone_set('Europe/Rome');

function resolve_base_url(): string
{
    $fromEnv = trim((string) getenv('APP_BASE_URL'));
    if ($fromEnv !== '') {
        return '/' . trim($fromEnv, '/');
    }

    $docRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $appRoot = realpath(__DIR__);

    if ($docRoot && $appRoot && str_starts_with($appRoot, $docRoot)) {
        $relative = trim(str_replace('\\', '/', substr($appRoot, strlen($docRoot))), '/');
        return $relative === '' ? '' : '/' . $relative;
    }

    return '';
}

define('APP_BASE_URL', resolve_base_url());

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('CSRF token non valido.');
    }
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        redirect('login.php');
    }
}

function require_role(string $role): void
{
    require_login();
    if ((current_user()['role_name'] ?? '') !== $role) {
        http_response_code(403);
        exit('Accesso negato.');
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim(APP_BASE_URL, '/');
    $suffix = ltrim($path, '/');

    if ($suffix === '') {
        return $base === '' ? '/' : $base . '/';
    }

    return ($base === '' ? '' : $base) . '/' . $suffix;
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}
