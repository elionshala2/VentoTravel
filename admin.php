<?php
session_start();
include 'config.php';
include 'includes/lang.php';
require_admin();
$counts = [];
foreach (['destinations'=>'destinations','rezervimet'=>'rezervimet','feedbacks'=>'feedbacks','faq'=>'faq'] as $k => $tbl) {
    $r = mysqli_query($conn, "SELECT COUNT(*) c FROM `$tbl`"); $counts[$k] = $r ? (int)$r->fetch_assoc()['c'] : 0;
}
$mode = 'admin'; $page = 'admin'; $seo_t = 'adm_title'; $seo_d = 'adm_sub';
include 'includes/header.php';
?>
<section class="page-head wrap reveal"><h1><?= t('adm_title') ?></h1><p><?= t('adm_sub') ?></p></section>
<section class="wrap admin-grid" style="margin-top:2rem">
  <a class="card tilt reveal" href="admin-destinations.php"><div class="icon">🗺️</div><h3><?= t('nav_adm_dest') ?></h3><p><b class="grad" style="font:800 2rem Sora" data-count="<?= $counts['destinations'] ?>">0</b></p></a>
  <a class="card tilt reveal" href="admin-bookings.php"><div class="icon">🎫</div><h3><?= t('nav_adm_book') ?></h3><p><b class="grad" style="font:800 2rem Sora" data-count="<?= $counts['rezervimet'] ?>">0</b></p></a>
  <a class="card tilt reveal" href="admin-feedbacks.php"><div class="icon">💬</div><h3><?= t('nav_adm_fb') ?></h3><p><b class="grad" style="font:800 2rem Sora" data-count="<?= $counts['feedbacks'] ?>">0</b></p></a>
  <a class="card tilt reveal" href="admin-faq.php"><div class="icon">❓</div><h3><?= t('nav_adm_faq') ?></h3><p><b class="grad" style="font:800 2rem Sora" data-count="<?= $counts['faq'] ?>">0</b></p></a>
</section>
<?php include 'includes/footer.php'; ?>
