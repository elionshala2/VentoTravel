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
<section class="wrap book" style="margin-top:2rem">
  <form method="post" class="panel reveal">
    <div class="field"><label><?= t('fbk_topic') ?></label>
      <div class="chips" id="chips">
        <button type="button" class="chip" data-chip="<?= h(t('topic_sugg')) ?>">💡 <?= t('topic_sugg') ?></button>
        <button type="button" class="chip" data-chip="<?= h(t('topic_compl')) ?>">❤️ <?= t('topic_compl') ?></button>
        <button type="button" class="chip" data-chip="<?= h(t('topic_issue')) ?>">🛠️ <?= t('topic_issue') ?></button>
        <button type="button" class="chip" data-chip="<?= h(t('topic_q')) ?>">❓ <?= t('topic_q') ?></button>
      </div></div>
    <div class="field"><label for="email"><?= t('email') ?></label><input id="email" type="email" name="email" required></div>
    <div class="field"><label for="m"><?= t('message') ?></label><textarea id="m" name="feedbackMessage" rows="6" maxlength="1000" required></textarea><small class="counter"><span id="cnt">0</span>/1000</small></div>
    <button class="btn btn--primary" type="submit"><?= t('send') ?></button>
  </form>
  <aside class="side">
    <div class="card tilt reveal"><div class="icon">👀</div><h3><?= t('fi1_t') ?></h3><p><?= t('fi1_d') ?></p></div>
    <div class="card tilt reveal"><div class="icon">⚡</div><h3><?= t('fi2_t') ?></h3><p><?= t('fi2_d') ?></p></div>
    <div class="card tilt reveal"><div class="icon">🌱</div><h3><?= t('fi3_t') ?></h3><p><?= t('fi3_d') ?></p></div>
    <a class="card reveal link-card" href="faq.php"><h3>❓ <?= t('faq_title') ?></h3><p><?= t('faq_sub') ?> →</p></a>
  </aside>
</section>
<?php include 'includes/footer.php'; ?>
