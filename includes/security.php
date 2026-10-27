<?php
// VentoTravel security helpers: sessions, headers, CSRF, rate limiting.

function vt_session_start(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function vt_security_headers(): void {
    if (headers_sent()) return;
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');
    header('Cross-Origin-Opener-Policy: same-origin');
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">'; }
function csrf_valid(): bool {
    $s = $_SESSION['csrf'] ?? ''; $p = $_POST['csrf'] ?? '';
    return is_string($p) && $s !== '' && hash_equals($s, $p);
}

function client_ip(): string { return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; }

// File-based rate limiter (no DB changes needed).
function rl_file(string $key): string {
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vento_rl';
    if (!is_dir($dir)) @mkdir($dir, 0700, true);
    return $dir . DIRECTORY_SEPARATOR . hash('sha256', $key) . '.json';
}
function rl_count(string $key, int $window): int {
    $f = rl_file($key); if (!is_file($f)) return 0;
    $a = json_decode((string)@file_get_contents($f), true) ?: [];
    $now = time(); return count(array_filter($a, fn($t) => $t > $now - $window));
}
function rl_hit(string $key, int $window): void {
    $f = rl_file($key); $now = time();
    $fp = @fopen($f, 'c+'); if (!$fp) return;
    flock($fp, LOCK_EX);
    $a = json_decode((string)stream_get_contents($fp), true) ?: [];
    $a = array_values(array_filter($a, fn($t) => $t > $now - $window)); $a[] = $now;
    ftruncate($fp, 0); rewind($fp); fwrite($fp, json_encode($a));
    flock($fp, LOCK_UN); fclose($fp);
}
function rl_clear(string $key): void { $f = rl_file($key); if (is_file($f)) @unlink($f); }
