<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_login();
$res = mysqli_query($conn, 'SELECT * FROM faq');
$sqmap = $LANG === 'sq' ? include 'includes/faq_sq.php' : [];
$mode = 'user'; $page = 'faq'; $seo_t = 'seo_faq_t'; $seo_d = 'seo_faq_d';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('faq_title') ?></h1><p><?= t('faq_sub') ?></p></section>
<section class="wrap">
  <div class="faq">
  <?php if ($res && $res->num_rows): while ($q = $res->fetch_assoc()): ?>
    <details class="reveal"><summary><?= h($sqmap[$q['question']][0] ?? $q['question']) ?></summary><p><?= h($sqmap[$q['question']][1] ?? $q['answer']) ?></p></details>
  <?php endwhile; else: ?><p class="empty"><?= t('no_faq') ?></p><?php endif; ?>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
