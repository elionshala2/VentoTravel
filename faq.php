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
  <div class="faq-search reveal">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
    <input id="faqSearch" type="search" placeholder="<?= h(t('faq_search')) ?>" aria-label="<?= h(t('faq_search')) ?>" autocomplete="off">
  </div>
  <div class="faq" id="faqList">
  <?php if ($res && $res->num_rows): while ($q = $res->fetch_assoc()): ?>
    <details class="reveal"><summary><?= h($sqmap[$q['question']][0] ?? $q['question']) ?></summary><p><?= h($sqmap[$q['question']][1] ?? $q['answer']) ?></p></details>
  <?php endwhile; else: ?><p class="empty"><?= t('no_faq') ?></p><?php endif; ?>
    <p class="empty" id="faqNone" hidden><?= t('faq_none') ?></p>
  </div>
  <div class="cta cta--small reveal">
    <h2><?= t('faq_still') ?></h2>
    <p><?= t('faq_still_d') ?></p>
    <a class="btn" href="feedback.php"><?= t('faq_cta') ?> →</a>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
