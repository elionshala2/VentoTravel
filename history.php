<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_login();
$uid = (int)($_SESSION['id'] ?? 0);
if (isset($_GET['remove_booking'])) {
    $id = (int)$_GET['remove_booking'];
    $st = $conn->prepare('DELETE FROM rezervimet WHERE id=? AND user_id=?'); $st->bind_param('ii', $id, $uid);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
if (isset($_GET['remove_feedback'])) {
    $id = (int)$_GET['remove_feedback'];
    $st = $conn->prepare('DELETE FROM feedbacks WHERE feedback_id=? AND user_id=?'); $st->bind_param('ii', $id, $uid);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
$b = $conn->prepare('SELECT * FROM rezervimet WHERE user_id=? ORDER BY id DESC'); $b->bind_param('i', $uid); $b->execute(); $bookings = $b->get_result();
$f = $conn->prepare('SELECT * FROM feedbacks WHERE user_id=? ORDER BY feedback_id DESC'); $f->bind_param('i', $uid); $f->execute(); $fbs = $f->get_result();
$mode = 'user'; $page = 'hist'; $seo_t = 'seo_hist_t'; $seo_d = 'seo_hist_d';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('hist_title') ?></h1><p><?= t('hist_sub') ?></p></section>
<section class="wrap cols" style="margin-top:2rem">
  <div>
    <h2 class="reveal">✈️ <?= t('bookings') ?></h2>
    <?php if ($bookings->num_rows): while ($r = $bookings->fetch_assoc()): ?>
      <div class="item reveal"><dl>
        <dt><?= t('booking_id') ?></dt><dd>#<?= (int)$r['id'] ?></dd>
        <dt><?= t('full_name') ?></dt><dd><?= h($r['fullname']) ?></dd>
        <dt><?= t('email') ?></dt><dd><?= h($r['email']) ?></dd>
        <dt><?= t('dest_id') ?></dt><dd><?= (int)$r['destination_id'] ?></dd>
        <dt><?= t('gender') ?></dt><dd><?= h($r['gender']) ?></dd>
        <dt><?= t('address') ?></dt><dd><?= h($r['address']) ?></dd>
        <dt><?= t('date') ?></dt><dd><?= h($r['reservation_date']) ?></dd></dl>
        <div class="acts"><a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="history.php?remove_booking=<?= (int)$r['id'] ?>"><?= t('delete') ?></a></div></div>
    <?php endwhile; else: ?><p class="empty"><?= t('no_bookings') ?></p><?php endif; ?>
  </div>
  <div>
    <h2 class="reveal">💬 <?= t('feedbacks') ?></h2>
    <?php if ($fbs->num_rows): while ($r = $fbs->fetch_assoc()): ?>
      <div class="item reveal"><dl>
        <dt><?= t('feedback_id') ?></dt><dd>#<?= (int)$r['feedback_id'] ?></dd>
        <dt><?= t('email') ?></dt><dd><?= h($r['email']) ?></dd>
        <dt><?= t('message') ?></dt><dd><?= h($r['message']) ?></dd>
        <dt><?= t('date') ?></dt><dd><?= h($r['submission_date']) ?></dd></dl>
        <div class="acts"><a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="history.php?remove_feedback=<?= (int)$r['feedback_id'] ?>"><?= t('delete') ?></a></div></div>
    <?php endwhile; else: ?><p class="empty"><?= t('no_fb') ?></p><?php endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
