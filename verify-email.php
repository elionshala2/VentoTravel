<?php
require_once 'includes/security.php';
vt_session_start(); vt_security_headers();
include 'config.php';
include 'includes/lang.php';
require_once 'includes/auth_lib.php';
require_once 'includes/account_lib.php';
$email = (string)($_SESSION['pending_signup'] ?? '');
if ($email === '') { header('Location: register.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) flash(t('err_csrf'), 'err');
    elseif (isset($_POST['resend'])) {
        $st = $conn->prepare('SELECT username, password_hash FROM pending_signups WHERE email=? LIMIT 1'); $st->bind_param('s', $email); $st->execute();
        $r = $st->get_result()->fetch_assoc();
        if (!$r) { unset($_SESSION['pending_signup']); header('Location: register.php'); exit; }
        if (rl_count('resend|' . $email, 3600) >= 5) flash(t('err_rate'), 'err');
        else { rl_hit('resend|' . $email, 3600); flash(vt_signup_start($conn, $r['username'], $email, $r['password_hash']) ? 'A new code was sent.' : 'Could not send: ' . ($GLOBALS['VT_MAIL_ERR'] ?: 'error'), 'ok'); }
    } elseif (rl_count('verify|' . client_ip(), 900) >= 15) flash(t('err_rate'), 'err');
    else {
        $res = vt_signup_finish($conn, $email, (string)($_POST['code'] ?? ''));
        if (isset($res['error'])) { unset($_SESSION['pending_signup']); flash('That email or username was taken in the meantime.', 'err'); }
        elseif ($res) { unset($_SESSION['pending_signup']); vt_finish_login($res['username'], (int)$res['id']); header('Location: index.php'); exit; }
        else { rl_hit('verify|' . client_ip(), 900); flash('Invalid or expired code.', 'err'); }
    }
}
$mode = 'public'; $page = 'reg'; $seo_t = 'seo_reg_t'; $seo_d = 'seo_reg_d';
include 'includes/header.php';
?>
<section class="acct">
  <div class="acct__card">
    <h1>Verify your email</h1>
    <p class="sub">We sent a 6-digit code to <strong><?= h($email) ?></strong>. Enter it to create your account.</p>
    <form method="post" autocomplete="off" class="stack"><?= csrf_field() ?>
      <div class="field"><label for="code">Code</label><input id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required autofocus></div>
      <div class="btnrow" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:center"><button class="btn btn--primary" type="submit">Verify &amp; create account</button>
      <button class="btn btn--ghost" type="submit" name="resend" value="1" formnovalidate>Resend code</button></div>
    </form>
    <p class="below"><a href="register.php">← Use a different email</a></p>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
