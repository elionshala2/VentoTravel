<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_admin();
if (isset($_POST['edit_feedback'])) {
    $id = (int)$_POST['id'];
    $st = $conn->prepare('UPDATE feedbacks SET email=?, message=? WHERE feedback_id=?');
    $st->bind_param('ssi', $_POST['new_email'], $_POST['new_message'], $id);
    $st->execute() ? flash(t('ok_updated')) : flash(t('err_generic'), 'err');
}
if (isset($_GET['remove_feedback'])) {
    $id = (int)$_GET['remove_feedback'];
    $st = $conn->prepare('DELETE FROM feedbacks WHERE feedback_id=?'); $st->bind_param('i', $id);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
$rows = mysqli_query($conn, 'SELECT * FROM feedbacks ORDER BY feedback_id DESC');
$mode = 'admin'; $page = 'adm_fb'; $seo_t = 'nav_adm_fb'; $seo_d = 'adm_sub';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('nav_adm_fb') ?></h1></section>
<section class="wrap admin-wrap" style="margin-top:1.5rem">
  <form id="editPanel" method="post" class="panel panel--wide" hidden>
    <h2><?= t('edit_fb') ?></h2><input type="hidden" name="id">
    <div class="field"><label><?= t('email') ?></label><input name="new_email" required></div>
    <div class="field"><label><?= t('message') ?></label><textarea name="new_message" required></textarea></div>
    <div class="btns"><button class="btn btn--primary" name="edit_feedback" type="submit"><?= t('save') ?></button><button class="btn btn--ghost" id="editCancel" type="button"><?= t('cancel') ?></button></div>
  </form>
  <h2 style="margin-top:1rem"><?= t('list_fb') ?></h2>
  <div class="grid" style="padding-bottom:0">
  <?php if ($rows->num_rows): while ($r = $rows->fetch_assoc()): ?>
    <div class="item reveal"><dl><dt>ID</dt><dd>#<?= (int)$r['feedback_id'] ?></dd><dt><?= t('email') ?></dt><dd><?= h($r['email']) ?></dd><dt><?= t('message') ?></dt><dd><?= h($r['message']) ?></dd><dt><?= t('date') ?></dt><dd><?= h($r['submission_date']) ?></dd></dl>
      <div class="acts"><button class="btn btn--ghost btn--sm" type="button" data-edit="<?= h(json_encode(['id'=>$r['feedback_id'],'email'=>$r['email'],'message'=>$r['message']])) ?>"><?= t('edit') ?></button>
      <a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="admin-feedbacks.php?remove_feedback=<?= (int)$r['feedback_id'] ?>"><?= t('delete') ?></a></div></div>
  <?php endwhile; else: ?><p class="empty"><?= t('empty') ?></p><?php endif; ?></div>
</section>
<?php include 'includes/footer.php'; ?>
