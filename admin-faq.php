<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_admin();
if (isset($_POST['add_question'])) {
    $st = $conn->prepare('INSERT INTO faq (question, answer) VALUES (?,?)'); $st->bind_param('ss', $_POST['question'], $_POST['answer']);
    $st->execute() ? flash(t('ok_added')) : flash(t('err_generic'), 'err');
}
if (isset($_POST['edit_question'])) {
    $id = (int)$_POST['id'];
    $st = $conn->prepare('UPDATE faq SET question=?, answer=? WHERE id=?'); $st->bind_param('ssi', $_POST['new_question'], $_POST['new_answer'], $id);
    $st->execute() ? flash(t('ok_updated')) : flash(t('err_generic'), 'err');
}
if (isset($_GET['remove_question'])) {
    $id = (int)$_GET['remove_question'];
    $st = $conn->prepare('DELETE FROM faq WHERE id=?'); $st->bind_param('i', $id);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
$rows = mysqli_query($conn, 'SELECT * FROM faq ORDER BY id DESC');
$mode = 'admin'; $page = 'adm_faq'; $seo_t = 'nav_adm_faq'; $seo_d = 'adm_sub';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('nav_adm_faq') ?></h1></section>
<section class="wrap admin-wrap" style="margin-top:1.5rem">
  <form id="editPanel" method="post" class="panel panel--wide" hidden>
    <h2><?= t('edit_faq') ?></h2><input type="hidden" name="id">
    <div class="field"><label><?= t('question') ?></label><input name="new_question" required></div>
    <div class="field"><label><?= t('answer') ?></label><textarea name="new_answer" required></textarea></div>
    <div class="btns"><button class="btn btn--primary" name="edit_question" type="submit"><?= t('save') ?></button><button class="btn btn--ghost" id="editCancel" type="button"><?= t('cancel') ?></button></div>
  </form>
  <form method="post" class="panel panel--wide reveal">
    <h2><?= t('add_faq') ?></h2>
    <div class="field"><label><?= t('question') ?></label><input name="question" required></div>
    <div class="field"><label><?= t('answer') ?></label><textarea name="answer" required></textarea></div>
    <button class="btn btn--primary" name="add_question" type="submit"><?= t('add') ?></button>
  </form>
  <h2 style="margin-top:1rem"><?= t('list_faq') ?></h2>
  <div class="grid" style="padding-bottom:0">
  <?php if ($rows->num_rows): while ($r = $rows->fetch_assoc()): ?>
    <div class="item reveal"><dl><dt><?= t('question') ?></dt><dd><?= h($r['question']) ?></dd><dt><?= t('answer') ?></dt><dd><?= h($r['answer']) ?></dd></dl>
      <div class="acts"><button class="btn btn--ghost btn--sm" type="button" data-edit="<?= h(json_encode(['id'=>$r['id'],'question'=>$r['question'],'answer'=>$r['answer']])) ?>"><?= t('edit') ?></button>
      <a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="admin-faq.php?remove_question=<?= (int)$r['id'] ?>"><?= t('delete') ?></a></div></div>
  <?php endwhile; else: ?><p class="empty"><?= t('empty') ?></p><?php endif; ?></div>
</section>
<?php include 'includes/footer.php'; ?>
