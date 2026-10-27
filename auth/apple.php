<?php
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth_lib.php';
vt_session_start(); vt_security_headers();
if (!apple_enabled()) vt_login_fail('err_social');
$state = bin2hex(random_bytes(16)); $nonce = bin2hex(random_bytes(16));
// Apple calls back with a cross-site POST, so the state lives in a SameSite=None cookie (HTTPS only).
setcookie('vt_apple', $state . '.' . $nonce, ['expires' => time() + 600, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'None']);
$q = http_build_query([
    'response_type' => 'code', 'response_mode' => 'form_post', 'client_id' => env('APPLE_CLIENT_ID'),
    'redirect_uri' => app_url() . '/auth/apple-callback.php', 'scope' => 'name email', 'state' => $state, 'nonce' => $nonce,
]);
header('Location: https://appleid.apple.com/auth/authorize?' . $q); exit;
