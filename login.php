<?php
require_once 'includes/security.php';
vt_session_start();
vt_security_headers();
include 'config.php';
include 'includes/lang.php';
if (isset($_SESSION['username'])) { header('Location: ' . ($_SESSION['username'] === 'admin' ? 'admin.php' : 'index.php')); exit; }

// Admin account (bcrypt of the old password). Change it: see README-SECURITY.txt
const ADMIN_EMAIL = 'admin@gmail.com';
const ADMIN_HASH  = '$2b$12$QbwU4SkSuWppy/YHYiu/sORSoiOzVR419soSALuJn7NTXvH3btFiy';
const DUMMY_HASH  = '$2y$10$usesomesillystringforsalt.u7bYH8mYx3p0o3m8mQzq2yQ0m4a9K';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $pw = (string)($_POST['password'] ?? '');
    $kUser = 'login|' . client_ip() . '|' . $email;
    $kIp = 'loginip|' . client_ip();

    if (!csrf_valid()) {
        flash(t('err_csrf'), 'err');
    } elseif (rl_count($kUser, 900) >= 5 || rl_count($kIp, 900) >= 20) {
        flash(t('err_rate'), 'err');
    } elseif ($email === '' || strlen($email) > 255 || strlen($pw) > 72 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        rl_hit($kUser, 900); rl_hit($kIp, 900);
        flash(t('err_login'), 'err');
    } else {
        $ok = false; $uid = null; $uname = null;
        if (hash_equals(ADMIN_EMAIL, $email)) {
            $ok = password_verify($pw, ADMIN_HASH); $uname = 'admin';
        } else {
            $st = $conn->prepare('SELECT id, username, password FROM regjistrimi WHERE email=? LIMIT 1');
            $st->bind_param('s', $email); $st->execute();
            $row = $st->get_result()->fetch_assoc();
            if ($row) {
                $stored = (string)$row['password'];
                if (preg_match('/^[a-f0-9]{32}$/i', $stored)) {          // legacy md5 -> upgrade on success
                    $ok = hash_equals(strtolower($stored), md5($pw));
                    if ($ok) {
                        $new = password_hash($pw, PASSWORD_DEFAULT);
                        $up = $conn->prepare('UPDATE regjistrimi SET password=? WHERE id=?');
                        $up->bind_param('si', $new, $row['id']); $up->execute();
                    }
                } else {
                    $ok = password_verify($pw, $stored);
                    if ($ok && password_needs_rehash($stored, PASSWORD_DEFAULT)) {
                        $new = password_hash($pw, PASSWORD_DEFAULT);
                        $up = $conn->prepare('UPDATE regjistrimi SET password=? WHERE id=?');
                        $up->bind_param('si', $new, $row['id']); $up->execute();
                    }
                }
                if ($ok) { $uid = (int)$row['id']; $uname = $row['username']; }
            } else {
                password_verify($pw, DUMMY_HASH); // equalise timing, hides whether email exists
            }
        }
        if ($ok) {
            rl_clear($kUser);
            session_regenerate_id(true);
            $_SESSION = ['username' => $uname, 'csrf' => bin2hex(random_bytes(32))];
            if ($uid !== null) $_SESSION['id'] = $uid;
            header('Location: ' . ($uname === 'admin' ? 'admin.php' : 'index.php')); exit;
        }
        rl_hit($kUser, 900); rl_hit($kIp, 900);
        flash(t('err_login'), 'err');
    }
}
$mode = 'public'; $page = 'login'; $seo_t = 'seo_login_t'; $seo_d = 'seo_login_d';
include 'includes/header.php';
?>
<section class="auth auth--split">
  <div class="split reveal">
    <aside class="split__visual" style="--img:url(img/Budva.jpg)">
      <span class="eyebrow">✈ VentoTravel</span>
      <div class="split__body">
        <h2><?= t('lv_title') ?></h2>
        <ul class="perks"><li><?= t('av_p1') ?></li><li><?= t('av_p2') ?></li><li><?= t('av_p3') ?></li></ul>
      </div>
      <div class="mini-stats"><div><b>50+</b><span><?= t('stat1') ?></span></div><div><b>12k+</b><span><?= t('stat2') ?></span></div><div><b>24/7</b><span><?= t('stat4') ?></span></div></div>
    </aside>
    <form method="post" class="split__form" autocomplete="on" novalidate>
      <?= csrf_field() ?>
      <h1><?= t('login_title') ?></h1>
      <p class="sub"><?= t('login_sub') ?></p>
      <div class="field"><label for="email"><?= t('email') ?></label><input id="email" type="email" name="email" autocomplete="email" maxlength="255" required value="<?= h($_POST['email'] ?? '') ?>"></div>
      <div class="field"><label for="pw"><?= t('password') ?></label>
        <div class="pw"><input id="pw" type="password" name="password" autocomplete="current-password" maxlength="72" required><button type="button" class="pw__toggle" data-show="<?= h(t('show_pw')) ?>" data-hide="<?= h(t('hide_pw')) ?>"><?= h(t('show_pw')) ?></button></div></div>
      <button class="btn btn--primary" type="submit" name="submit"><?= t('login_btn') ?></button>
      <p class="secure">🔒 <?= t('trust_secure') ?></p>
      <p class="below"><?= t('no_account') ?> <a href="register.php"><?= t('signup_link') ?></a> · <a href="index.php">← <?= t('back_home') ?></a></p>
    </form>
  </div>
</section>
<script src="js/auth.js" defer></script>
<?php include 'includes/footer.php'; ?>
