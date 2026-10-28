<?php
require_once 'includes/security.php';
vt_session_start(); vt_security_headers();
include 'config.php';
include 'includes/lang.php';
require_once 'includes/auth_lib.php';
require_once 'includes/account_lib.php';
$p = $_SESSION['pending_2fa'] ?? null;
if (!$p || $p['exp'] < time()) { unset($_SESSION['pending_2fa']); header('Location: login.php'); exit; }
$uid = (int)$p['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) flash(t('err_csrf'), 'err');
    elseif (isset($_POST['resend'])) {
        $q = $conn->prepare('SELECT email FROM regjistrimi WHERE id=?'); $q->bind_param('i', $uid); $q->execute();
        $em = (string)($q->get_result()->fetch_assoc()['email'] ?? '');
        flash(vt_code_send($conn, $uid, 'login', $em, $p['username']) ? 'A new code was sent.' : 'Could not send a code: ' . ($GLOBALS['VT_MAIL_ERR'] ?: 'too many requests'), 'ok');
    } elseif (rl_count('2fa|' . client_ip(), 900) >= 10) flash(t('err_rate'), 'err');
    elseif (vt_code_check($conn, $uid, 'login', (string)($_POST['code'] ?? ''))) {
        vt_finish_login($p['username'], $uid); header('Location: index.php'); exit;
    } else { rl_hit('2fa|' . client_ip(), 900); flash('Invalid or expired code.', 'err'); }
}
$mode = 'public'; $page = 'login'; $seo_t = 'seo_login_t'; $seo_d = 'seo_login_d';
include 'includes/header.php';
?>
<section class="acct">
  <div class="acct__card">
    <h1>Two-factor verification</h1>
    <p class="sub">We emailed a 6-digit code to the address on your account. Enter it to finish signing in.</p>
    <form method="post" autocomplete="off" class="stack"><?= csrf_field() ?>
      <div class="field"><label for="code">Code</label><input id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required autofocus></div>
      <div class="btnrow" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:center"><button class="btn btn--primary" type="submit">Verify</button>
      <button class="btn btn--ghost" type="submit" name="resend" value="1" formnovalidate>Resend code</button></div>
    </form>
    <p class="below"><a href="login.php">← Back to login</a></p>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
