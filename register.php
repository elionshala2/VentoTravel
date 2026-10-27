<?php
require_once 'includes/security.php';
vt_session_start();
vt_security_headers();
include 'config.php';
include 'includes/lang.php';
if (isset($_SESSION['username'])) { header('Location: index.php'); exit; }

$ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $username = preg_replace('/\s+/u', ' ', $username);
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $pw = (string)($_POST['password'] ?? '');
    $kIp = 'reg|' . client_ip();

    if (!csrf_valid()) flash(t('err_csrf'), 'err');
    elseif (rl_count($kIp, 3600) >= 8) flash(t('err_rate'), 'err');
    elseif (mb_strlen($username) < 3 || mb_strlen($username) > 30 || !preg_match('/^[\p{L}\p{N}_. -]+$/u', $username)) flash(t('err_user_fmt'), 'err');
    elseif (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) flash(t('err_email_fmt'), 'err');
    elseif ($email === 'admin@gmail.com') flash(t('err_email_admin'), 'err');
    elseif (strtolower($username) === 'admin') flash(t('err_user_admin'), 'err');
    elseif (strlen($pw) < 8 || strlen($pw) > 72) flash(t('err_pw_len'), 'err');
    elseif (!hash_equals($pw, (string)($_POST['cpassword'] ?? ''))) flash(t('err_pw'), 'err');
    else {
        rl_hit($kIp, 3600);
        $st = $conn->prepare('SELECT id FROM regjistrimi WHERE email=? LIMIT 1');
        $st->bind_param('s', $email); $st->execute();
        if ($st->get_result()->num_rows > 0) flash(t('err_email_taken'), 'err');
        else {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $st = $conn->prepare('INSERT INTO regjistrimi (username, email, password) VALUES (?,?,?)');
            $st->bind_param('sss', $username, $email, $hash);
            if ($st->execute()) { flash(t('ok_reg')); $ok = true; unset($_SESSION['csrf']); } else flash(t('err_generic'), 'err');
        }
    }
}
$mode = 'public'; $page = 'reg'; $seo_t = 'seo_reg_t'; $seo_d = 'seo_reg_d';
include 'includes/header.php';
?>
<section class="auth auth--split">
  <div class="split reveal">
    <aside class="split__visual" style="--img:url(img/Greqi.jpg)">
      <span class="eyebrow">✈ VentoTravel</span>
      <div class="split__body">
        <h2><?= t('rv_title') ?></h2>
        <ul class="perks"><li><?= t('av_p1') ?></li><li><?= t('av_p2') ?></li><li><?= t('av_p3') ?></li></ul>
      </div>
      <div class="mini-stats"><div><b>50+</b><span><?= t('stat1') ?></span></div><div><b>12k+</b><span><?= t('stat2') ?></span></div><div><b>24/7</b><span><?= t('stat4') ?></span></div></div>
    </aside>
    <form method="post" class="split__form" novalidate>
      <?= csrf_field() ?>
      <h1><?= t('reg_title') ?></h1>
      <p class="sub"><?= t('reg_sub') ?></p>
      <div class="field"><label for="u"><?= t('username') ?></label><input id="u" type="text" name="username" autocomplete="username" minlength="3" maxlength="30" required value="<?= $ok ? '' : h($_POST['username'] ?? '') ?>"></div>
      <div class="field"><label for="e"><?= t('email') ?></label><input id="e" type="email" name="email" autocomplete="email" maxlength="255" required value="<?= $ok ? '' : h($_POST['email'] ?? '') ?>"></div>
      <div class="row2">
        <div class="field"><label for="p"><?= t('password') ?></label><input id="p" type="password" name="password" autocomplete="new-password" minlength="8" maxlength="72" required></div>
        <div class="field"><label for="cp"><?= t('confirm_pw') ?></label><input id="cp" type="password" name="cpassword" autocomplete="new-password" minlength="8" maxlength="72" required></div>
      </div>
      <p class="hint"><?= t('pw_hint') ?></p>
      <button class="btn btn--primary" type="submit" name="submit"><?= t('reg_btn') ?></button>
      <p class="secure">🔒 <?= t('trust_secure') ?></p>
      <p class="below"><?= t('has_account') ?> <a href="login.php"><?= t('login_link') ?></a> · <a href="index.php">← <?= t('back_home') ?></a></p>
    </form>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
