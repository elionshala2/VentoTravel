<?php
session_start();
include 'config.php';
include 'includes/lang.php';
if (isset($_SESSION['username'])) { header('Location: ' . ($_SESSION['username'] === 'admin' ? 'admin.php' : 'index.php')); exit; }

if (isset($_POST['submit'])) {
    $email = trim($_POST['email'] ?? '');
    $password = md5($_POST['password'] ?? '');
    if ($email === 'admin@gmail.com' && $password === md5('admin123')) {
        $_SESSION['username'] = 'admin';
        header('Location: admin.php'); exit;
    }
    $st = $conn->prepare('SELECT id, username FROM regjistrimi WHERE email=? AND password=?');
    $st->bind_param('ss', $email, $password);
    $st->execute();
    $row = $st->get_result()->fetch_assoc();
    if ($row) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['id'];
        header('Location: index.php'); exit;
    }
    flash(t('err_login'), 'err');
}
$mode = 'public'; $page = 'login'; $seo_t = 'seo_login_t'; $seo_d = 'seo_login_d';
include 'includes/header.php';
?>
<section class="auth">
  <form method="post" class="panel reveal">
    <h1><?= t('login_title') ?></h1>
    <p class="sub"><?= t('login_sub') ?></p>
    <div class="field"><label for="email"><?= t('email') ?></label><input id="email" type="email" name="email" autocomplete="email" required value="<?= h($_POST['email'] ?? '') ?>"></div>
    <div class="field"><label for="pw"><?= t('password') ?></label><input id="pw" type="password" name="password" autocomplete="current-password" required></div>
    <button class="btn btn--primary" type="submit" name="submit"><?= t('login_btn') ?></button>
    <p class="below"><?= t('no_account') ?> <a href="register.php"><?= t('signup_link') ?></a></p>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
