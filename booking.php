<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_login();
$selected = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['fullname']); $email = trim($_POST['email']); $phone = trim($_POST['phone']);
    $did = (int)$_POST['destination_id']; $gender = $_POST['gender']; $addr = trim($_POST['address']); $uid = (int)($_SESSION['id'] ?? 0);
    $selected = $did;
    $st = $conn->prepare('SELECT id FROM destinations WHERE id=?'); $st->bind_param('i', $did); $st->execute();
    if ($st->get_result()->num_rows > 0) {
        $st = $conn->prepare('INSERT INTO rezervimet (fullname,email,phone,destination_id,gender,address,user_id) VALUES (?,?,?,?,?,?,?)');
        $st->bind_param('sssissi', $name, $email, $phone, $did, $gender, $addr, $uid);
        if ($st->execute()) { flash(t('ok_booked')); $selected = 0; } else flash(t('err_generic'), 'err');
    } else flash(t('err_dest'), 'err');
}
$dests = mysqli_query($conn, 'SELECT id, title FROM destinations ORDER BY title');
$mode = 'user'; $page = 'book'; $seo_t = 'seo_book_t'; $seo_d = 'seo_book_d';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('book_title') ?></h1><p><?= t('book_sub') ?></p></section>
<section class="wrap" style="margin-top:2rem">
  <form method="post" class="panel reveal">
    <div class="field"><label for="fullname"><?= t('full_name') ?></label><input id="fullname" name="fullname" required autocomplete="name"></div>
    <div class="row2">
      <div class="field"><label for="email"><?= t('email') ?></label><input id="email" type="email" name="email" required autocomplete="email"></div>
      <div class="field"><label for="phone"><?= t('phone') ?></label><input id="phone" type="tel" name="phone" required autocomplete="tel"></div>
    </div>
    <div class="field"><label for="destination_id"><?= t('destination') ?></label>
      <select id="destination_id" name="destination_id" required><option value=""><?= t('choose_dest') ?></option>
        <?php while ($d = $dests->fetch_assoc()): ?><option value="<?= (int)$d['id'] ?>" <?= $selected === (int)$d['id'] ? 'selected' : '' ?>><?= h($d['title']) ?> (#<?= (int)$d['id'] ?>)</option><?php endwhile; ?>
      </select></div>
    <div class="row2">
      <div class="field"><label for="gender"><?= t('gender') ?></label><select id="gender" name="gender" required><option value="Male"><?= t('male') ?></option><option value="Female"><?= t('female') ?></option><option value="Other"><?= t('other') ?></option></select></div>
      <div class="field"><label for="address"><?= t('address') ?></label><input id="address" name="address" required autocomplete="street-address"></div>
    </div>
    <button class="btn btn--primary" type="submit"><?= t('book_btn') ?></button>
  </form>
</section>
<?php include 'includes/footer.php'; ?>
