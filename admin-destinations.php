<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_admin();
if (isset($_POST['add_destination'])) {
    $st = $conn->prepare('INSERT INTO destinations (title,photo_url,small_description,big_description,price) VALUES (?,?,?,?,?)');
    $st->bind_param('sssss', $_POST['title'], $_POST['photo_url'], $_POST['small_description'], $_POST['big_description'], $_POST['price']);
    $st->execute() ? flash(t('ok_added')) : flash(t('err_generic'), 'err');
}
if (isset($_POST['edit_destination'])) {
    $id = (int)$_POST['id'];
    $st = $conn->prepare('UPDATE destinations SET title=?,photo_url=?,small_description=?,big_description=?,price=? WHERE id=?');
    $st->bind_param('sssssi', $_POST['new_title'], $_POST['new_photo_url'], $_POST['new_small_description'], $_POST['new_big_description'], $_POST['new_price'], $id);
    $st->execute() ? flash(t('ok_updated')) : flash(t('err_generic'), 'err');
}
if (isset($_GET['remove_destination'])) {
    $id = (int)$_GET['remove_destination'];
    $st = $conn->prepare('DELETE FROM destinations WHERE id=?'); $st->bind_param('i', $id);
    $st->execute() ? flash(t('ok_deleted')) : flash(t('err_generic'), 'err');
}
$rows = mysqli_query($conn, 'SELECT * FROM destinations ORDER BY id DESC');
$mode = 'admin'; $page = 'adm_dest'; $seo_t = 'nav_adm_dest'; $seo_d = 'adm_sub';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('nav_adm_dest') ?></h1></section>
<section class="wrap admin-wrap" style="margin-top:1.5rem">
  <form id="editPanel" method="post" class="panel panel--wide" hidden>
    <h2><?= t('edit_dest') ?></h2><input type="hidden" name="id">
    <div class="row2"><div class="field"><label><?= t('title') ?></label><input name="new_title" required></div><div class="field"><label><?= t('price') ?> (€)</label><input name="new_price" required></div></div>
    <div class="field"><label><?= t('photo_url') ?></label><input name="new_photo_url" required></div>
    <div class="field"><label><?= t('small_desc') ?></label><input name="new_small_description" required></div>
    <div class="field"><label><?= t('big_desc') ?></label><textarea name="new_big_description" required></textarea></div>
    <div class="btns"><button class="btn btn--primary" name="edit_destination" type="submit"><?= t('save') ?></button><button class="btn btn--ghost" id="editCancel" type="button"><?= t('cancel') ?></button></div>
  </form>
  <form method="post" class="panel panel--wide reveal">
    <h2><?= t('add_dest') ?></h2>
    <div class="row2"><div class="field"><label><?= t('title') ?></label><input name="title" required></div><div class="field"><label><?= t('price') ?> (€)</label><input name="price" required></div></div>
    <div class="field"><label><?= t('photo_url') ?></label><input name="photo_url" placeholder="img/Example.jpg" required></div>
    <div class="field"><label><?= t('small_desc') ?></label><input name="small_description" required></div>
    <div class="field"><label><?= t('big_desc') ?></label><textarea name="big_description" required></textarea></div>
    <button class="btn btn--primary" name="add_destination" type="submit"><?= t('add') ?></button>
  </form>
  <div class="wrap" style="width:100%"><h2 style="margin:1rem 0"><?= t('list_dest') ?></h2><div class="grid" style="padding-bottom:0">
  <?php while ($r = $rows->fetch_assoc()): ?>
    <div class="item reveal"><dl><dt>ID</dt><dd>#<?= (int)$r['id'] ?></dd><dt><?= t('title') ?></dt><dd><?= h($r['title']) ?></dd><dt><?= t('photo_url') ?></dt><dd><?= h($r['photo_url']) ?></dd><dt><?= t('small_desc') ?></dt><dd><?= h($r['small_description']) ?></dd><dt><?= t('price') ?></dt><dd><?= h($r['price']) ?> €</dd></dl>
      <div class="acts"><button class="btn btn--ghost btn--sm" type="button" data-edit="<?= h(json_encode(['id'=>$r['id'],'title'=>$r['title'],'photo_url'=>$r['photo_url'],'small_description'=>$r['small_description'],'big_description'=>$r['big_description'],'price'=>$r['price']])) ?>"><?= t('edit') ?></button>
      <a class="btn btn--danger btn--sm" data-confirm="<?= h(t('confirm_del')) ?>" href="admin-destinations.php?remove_destination=<?= (int)$r['id'] ?>"><?= t('delete') ?></a></div></div>
  <?php endwhile; ?></div></div>
</section>
<?php include 'includes/footer.php'; ?>
