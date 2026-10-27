<?php
session_start();
include 'config.php';
include 'includes/lang.php';
if (isset($_SESSION['username'])) { header('Location: index.php'); exit; }

$ok = false;
if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pw = $_POST['password'] ?? '';
    if ($email === 'admin@gmail.com') flash(t('err_email_admin'), 'err');
    elseif (strtolower($username) === 'admin') flash(t('err_user_admin'), 'err');
    elseif ($pw !== ($_POST['cpassword'] ?? '')) flash(t('err_pw'), 'err');
    else {
        $st = $conn->prepare('SELECT id FROM regjistrimi WHERE email=?');
        $st->bind_param('s', $email); $st->execute();
        if ($st->get_result()->num_rows > 0) flash(t('err_email_taken'), 'err');
        else {
            $hash = md5($pw);
            $st = $conn->prepare('INSERT INTO regjistrimi (username, email, password) VALUES (?,?,?)');
            $st->bind_param('sss', $username, $email, $hash);
            if ($st->execute()) { flash(t('ok_reg')); $ok = true; } else flash(t('err_generic'), 'err');
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
      <h2><?= t('rv_title') ?></h2>
      <ul class="perks">
        <li><?= t('av_p1') ?></li>
        <li><?= t('av_p2') ?></li>
        <li><?= t('av_p3') ?></li>
      </ul>
      <div class="mini-stats"><div><b>50+</b><span><?= t('stat1') ?></span></div><div><b>12k+</b><span><?= t('stat2') ?></span></div><div><b>24/7</b><span><?= t('stat4') ?></span></div></div>
    </aside>
    <form method="post" class="split__form">
      <h1><?= t('reg_title') ?></h1>
      <p class="sub"><?= t('reg_sub') ?></p>
      <div class="field"><label for="u"><?= t('username') ?></label><input id="u" type="text" name="username" autocomplete="username" required value="<?= $ok ? '' : h($_POST['username'] ?? '') ?>"></div>
      <div class="field"><label for="e"><?= t('email') ?></label><input id="e" type="email" name="email" autocomplete="email" required value="<?= $ok ? '' : h($_POST['email'] ?? '') ?>"></div>
      <div class="row2">
        <div class="field"><label for="p"><?= t('password') ?></label><input id="p" type="password" name="password" autocomplete="new-password" required></div>
        <div class="field"><label for="cp"><?= t('confirm_pw') ?></label><input id="cp" type="password" name="cpassword" autocomplete="new-password" required></div>
      </div>
      <button class="btn btn--primary" type="submit" name="submit"><?= t('reg_btn') ?></button>
      <p class="secure">🔒 <?= t('trust_secure') ?></p>
      <p class="below"><?= t('has_account') ?> <a href="login.php"><?= t('login_link') ?></a> · <a href="index.php">← <?= t('back_home') ?></a></p>
    </form>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
