<?php
require_once 'includes/security.php';
vt_session_start(); vt_security_headers();
include 'config.php';
include 'includes/lang.php';
$doc = 'terms';
include 'includes/legal_view.php';
