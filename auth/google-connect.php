<?php
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/auth_lib.php';
vt_session_start(); vt_security_headers();
if (empty($_SESSION['id']) || !google_enabled()) { header('Location: ../login.php'); exit; }
$p = vt_google_provider();
$url = $p->getAuthorizationUrl(['scope' => ['openid', 'email', 'profile'], 'prompt' => 'select_account']);
$_SESSION['oauth_state'] = $p->getState();
$_SESSION['oauth_link'] = (int)$_SESSION['id'];
header('Location: ' . $url); exit;
