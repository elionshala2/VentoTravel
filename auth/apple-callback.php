<?php
use Firebase\JWT\JWT; use Firebase\JWT\JWK;
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth_lib.php';
vt_session_start(); vt_security_headers();
if (!apple_enabled() || $_SERVER['REQUEST_METHOD'] !== 'POST') vt_login_fail('err_social');
$cookie = (string)($_COOKIE['vt_apple'] ?? '');
setcookie('vt_apple', '', ['expires' => time() - 3600, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None']);
$parts = explode('.', $cookie); $cs = $parts[0] ?? ''; $cn = $parts[1] ?? '';
$state = $_POST['state'] ?? '';
if ($cs === '' || $cn === '' || !is_string($state) || !hash_equals($cs, $state)) vt_login_fail('err_csrf');
if (isset($_POST['error']) || empty($_POST['code']) || !is_string($_POST['code'])) vt_login_fail('err_social');
if (rl_count('social|' . client_ip(), 900) >= 20) vt_login_fail('err_rate');
rl_hit('social|' . client_ip(), 900);
try {
    $key = @file_get_contents((string)env('APPLE_PRIVATE_KEY_PATH'));
    if (!$key) throw new RuntimeException('Apple private key not readable');
    $now = time();
    $secret = JWT::encode(['iss' => env('APPLE_TEAM_ID'), 'iat' => $now, 'exp' => $now + 300, 'aud' => 'https://appleid.apple.com', 'sub' => env('APPLE_CLIENT_ID')], $key, 'ES256', env('APPLE_KEY_ID'));
    $resp = vt_http('https://appleid.apple.com/auth/token', [
        'client_id' => env('APPLE_CLIENT_ID'), 'client_secret' => $secret, 'code' => $_POST['code'],
        'grant_type' => 'authorization_code', 'redirect_uri' => app_url() . '/auth/apple-callback.php',
    ]);
    $tok = json_decode((string)$resp, true);
    if (empty($tok['id_token'])) throw new RuntimeException('No id_token');
    $jwks = json_decode((string)vt_http('https://appleid.apple.com/auth/keys'), true);
    JWT::$leeway = 60;
    $c = JWT::decode($tok['id_token'], JWK::parseKeySet($jwks, 'RS256'));
    if (($c->iss ?? '') !== 'https://appleid.apple.com' || ($c->aud ?? '') !== env('APPLE_CLIENT_ID') || !hash_equals($cn, (string)($c->nonce ?? ''))) throw new RuntimeException('Claim mismatch');
    if (!in_array($c->email_verified ?? false, [true, 'true'], true) || empty($c->email) || empty($c->sub)) throw new RuntimeException('Unverified email');
} catch (Throwable $e) { error_log('Apple login: ' . $e->getMessage()); vt_login_fail('err_social'); }
$name = '';
if (!empty($_POST['user']) && is_string($_POST['user'])) { $u = json_decode($_POST['user'], true); $name = trim(($u['name']['firstName'] ?? '') . ' ' . ($u['name']['lastName'] ?? '')); }
$err = vt_social_login($conn, 'apple', (string)$c->sub, (string)$c->email, $name);
if ($err) vt_login_fail($err);
header('Location: ../index.php'); exit;
