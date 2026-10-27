<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); $msg = trim($_POST['feedbackMessage']); $uid = (int)($_SESSION['id'] ?? 0);
    $st = $conn->prepare('INSERT INTO feedbacks (user_id,email,message,submission_date) VALUES (?,?,?,NOW())');
    $st->bind_param('iss', $uid, $email, $msg);
    $st->execute() ? flash(t('ok_fb')) : flash(t('err_generic'), 'err');
}
$mode = 'user'; $page = 'fb'; $seo_t = 'seo_fb_t'; $seo_d = 'seo_fb_d';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('fb_title') ?></h1><p><?= t('fb_sub') ?></p></section>
<section class="wrap" style="margin-top:2rem">
  <form method="post" class="panel reveal">
    <div class="field"><label for="email"><?= t('email') ?></label><input id="email" type="email" name="email" required></div>
    <div class="field"><label for="m"><?= t('message') ?></label><textarea id="m" name="feedbackMessage" rows="5" required></textarea></div>
    <button class="btn btn--primary" type="submit"><?= t('send') ?></button>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
