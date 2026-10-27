<?php
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth_lib.php';
vt_session_start(); vt_security_headers();
if (!google_enabled()) vt_login_fail('err_social');
$p = vt_google_provider();
$url = $p->getAuthorizationUrl(['scope' => ['openid', 'email', 'profile'], 'prompt' => 'select_account']);
$_SESSION['oauth_state'] = $p->getState();
header('Location: ' . $url); exit;
