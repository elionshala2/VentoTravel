<?php
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth_lib.php';
vt_session_start(); vt_security_headers();
if (!google_enabled()) vt_login_fail('err_social');
$state = $_GET['state'] ?? ''; $exp = $_SESSION['oauth_state'] ?? ''; unset($_SESSION['oauth_state']);
if (!is_string($state) || $exp === '' || !hash_equals($exp, $state)) vt_login_fail('err_csrf');
if (isset($_GET['error']) || empty($_GET['code']) || !is_string($_GET['code'])) vt_login_fail('err_social');
if (rl_count('social|' . client_ip(), 900) >= 20) vt_login_fail('err_rate');
rl_hit('social|' . client_ip(), 900);
try {
    $p = vt_google_provider();
    $token = $p->getAccessToken('authorization_code', ['code' => $_GET['code']]);
    $o = $p->getResourceOwner($token); $d = $o->toArray();
} catch (Throwable $e) { error_log('Google login: ' . $e->getMessage()); vt_login_fail('err_social'); }
if (empty($d['email_verified']) || empty($d['email'])) vt_login_fail('err_social');
if (!empty($_SESSION['oauth_link'])) {
    $lu = (int)$_SESSION['oauth_link']; unset($_SESSION['oauth_link']);
    $dest = rtrim(app_url(), '/') . '/account.php';
    if ($lu <= 0 || $lu !== (int)($_SESSION['id'] ?? 0)) { header('Location: ' . $dest); exit; }
    $pu = (string)$o->getId();
    $c = $conn->prepare('SELECT user_id FROM oauth_accounts WHERE provider=? AND provider_uid=?'); $pg = 'google'; $c->bind_param('ss', $pg, $pu); $c->execute();
    $ex = $c->get_result()->fetch_assoc();
    if ($ex && (int)$ex['user_id'] !== $lu) { header('Location: ' . $dest . '?glink=taken'); exit; }
    if (!$ex) { $i = $conn->prepare('INSERT INTO oauth_accounts (user_id, provider, provider_uid) VALUES (?,?,?)'); $i->bind_param('iss', $lu, $pg, $pu); $i->execute(); }
    header('Location: ' . $dest . '?glink=ok'); exit;
}
$err = vt_social_login($conn, 'google', (string)$o->getId(), (string)$d['email'], (string)($d['name'] ?? ''));
if ($err) vt_login_fail($err);
header('Location: ../index.php'); exit;
