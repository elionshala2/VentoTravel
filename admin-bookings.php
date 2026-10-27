<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_admin();
if (isset($_POST['edit_reservation'])) {
    $id = (int)$_POST['id']; $did = (int)$_POST['new_destination_id']; $uid = (int)$_POST['new_user_id'];
    $st = $conn->prepare('UPDATE rezervimet SET fullname=?,email=?,phone=?,destination_id=?,gender=?,address=?,reservation_date=?,user_id=? WHERE id=?');
    $st->bind_param('sssisssii', $_POST['new_fullname'], $_POST['new_email'], $_POST['new_phone'], $did, $_POST['new_gender'], $_POST['new_address'], $_POST['new_reservation_date'], $uid, $id);
    $st->execute() ? flash(t('ok_updated')) : flash(t('err_generic'), 'err');
}
if (isset($_GET['remove_reservation'])) {
    $id = (int)$_GET['remove_reservation'];
    $st = $conn->prepare('DELETE FROM rezervimet WHERE id=?'); $st->bind_param('i', $id);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
$rows = mysqli_query($conn, 'SELECT * FROM rezervimet ORDER BY id DESC');
$mode = 'admin'; $page = 'adm_book'; $seo_t = 'nav_adm_book'; $seo_d = 'adm_sub';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('nav_adm_book') ?></h1></section>
<section class="wrap admin-wrap" style="margin-top:1.5rem">
  <form id="editPanel" method="post" class="panel panel--wide" hidden>
    <h2><?= t('edit_book') ?></h2><input type="hidden" name="id">
    <div class="row2"><div class="field"><label><?= t('full_name') ?></label><input name="new_fullname" required></div><div class="field"><label><?= t('email') ?></label><input name="new_email" required></div></div>
    <div class="row2"><div class="field"><label><?= t('phone') ?></label><input name="new_phone" required></div><div class="field"><label><?= t('dest_id') ?></label><input name="new_destination_id" required></div></div>
    <div class="row2"><div class="field"><label><?= t('gender') ?></label><input name="new_gender" required></div><div class="field"><label><?= t('address') ?></label><input name="new_address"></div></div>
    <div class="row2"><div class="field"><label><?= t('reservation_date') ?></label><input name="new_reservation_date" required></div><div class="field"><label><?= t('user_id') ?></label><input name="new_user_id"></div></div>
    <div class="btns"><button class="btn btn--primary" name="edit_reservation" type="submit"><?= t('save') ?></button><button class="btn btn--ghost" id="editCancel" type="button"><?= t('cancel') ?></button></div>
  </form>
  <h2 style="margin-top:1rem"><?= t('list_book') ?></h2>
  <div class="grid" style="padding-bottom:0">
  <?php if ($rows->num_rows): while ($r = $rows->fetch_assoc()): ?>
    <div class="item reveal"><dl><dt>ID</dt><dd>#<?= (int)$r['id'] ?></dd><dt><?= t('full_name') ?></dt><dd><?= h($r['fullname']) ?></dd><dt><?= t('email') ?></dt><dd><?= h($r['email']) ?></dd><dt><?= t('phone') ?></dt><dd><?= h($r['phone']) ?></dd><dt><?= t('dest_id') ?></dt><dd><?= (int)$r['destination_id'] ?></dd><dt><?= t('gender') ?></dt><dd><?= h($r['gender']) ?></dd><dt><?= t('address') ?></dt><dd><?= h($r['address']) ?></dd><dt><?= t('date') ?></dt><dd><?= h($r['reservation_date']) ?></dd><dt><?= t('user_id') ?></dt><dd><?= (int)$r['user_id'] ?></dd></dl>
      <div class="acts"><button class="btn btn--ghost btn--sm" type="button" data-edit="<?= h(json_encode(['id'=>$r['id'],'fullname'=>$r['fullname'],'email'=>$r['email'],'phone'=>$r['phone'],'destination_id'=>$r['destination_id'],'gender'=>$r['gender'],'address'=>$r['address'],'reservation_date'=>$r['reservation_date'],'user_id'=>$r['user_id']])) ?>"><?= t('edit') ?></button>
      <a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="admin-bookings.php?remove_reservation=<?= (int)$r['id'] ?>"><?= t('delete') ?></a></div></div>
  <?php endwhile; else: ?><p class="empty"><?= t('empty') ?></p><?php endif; ?></div>
</section>
<?php include 'includes/footer.php'; ?>
