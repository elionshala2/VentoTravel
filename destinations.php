<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_login();
$res = mysqli_query($conn, 'SELECT * FROM destinations');
$mode = 'user'; $page = 'dest'; $seo_t = 'seo_dest_t'; $seo_d = 'seo_dest_d';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('dest_title') ?></h1><p><?= t('dest_sub') ?></p></section>
<section class="wrap">
<?php if ($res && $res->num_rows > 0): ?>
  <div class="toolbar"><label class="select-wrap" for="sort"><?= t('sort_by') ?>
    <select id="sort"><option value="low"><?= t('sort_low') ?></option><option value="high"><?= t('sort_high') ?></option><option value="new"><?= t('sort_new') ?></option><option value="old"><?= t('sort_old') ?></option></select></label></div>
  <div class="grid" id="destGrid">
  <?php while ($d = $res->fetch_assoc()): $disc = round($d['price'] * 0.95, 2); ?>
    <article class="dest" data-price="<?= h($d['price']) ?>" data-id="<?= (int)$d['id'] ?>">
      <div class="dest__img"><span class="badge"><?= t('off5') ?></span><img src="<?= h($d['photo_url']) ?>" alt="<?= h($d['title']) ?>"></div>
      <div class="dest__body">
        <h2><?= h($d['title']) ?></h2>
        <p class="dest__small"><?= h($d['small_description']) ?></p>
        <div class="dest__big"><div><?= h($d['big_description']) ?></div></div>
        <button class="dest__toggle" data-more="<?= h(t('more')) ?>" data-less="<?= h(t('less')) ?>"><?= t('more') ?></button>
        <div class="dest__foot">
          <div class="price"><s><?= t('price') ?>: <?= h($d['price']) ?> €</s><strong><?= $disc ?> €</strong></div>
          <a class="btn btn--primary btn--sm" href="booking.php?id=<?= (int)$d['id'] ?>"><?= t('book_this') ?></a>
        </div>
      </div>
    </article>
  <?php endwhile; ?>
  </div>
<?php else: ?>
  <p class="empty"><?= t('no_dest') ?></p>
<?php endif; ?>
</section>
<div class="offer" id="offer"><span><?= t('offer') ?></span> <a href="booking.php"><?= t('offer_cta') ?></a><button aria-label="<?= h(t('close')) ?>">&times;</button></div>
<?php include 'includes/footer.php'; ?>
