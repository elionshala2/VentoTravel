<?php
session_start();
include 'config.php';
include 'includes/lang.php';
$loggedIn = isset($_SESSION['username']);
$mode = $loggedIn ? 'user' : 'guest'; $page = 'home'; $seo_t = 'seo_home_t'; $seo_d = 'seo_home_d';
include 'includes/header.php';
?>
<section class="hero">
  <div class="hero__bg"></div>
  <div class="wrap">
    <span class="eyebrow reveal"><?= t('hero_eyebrow') ?></span>
    <h1 class="reveal" style="--d:100ms"><?= t('hero_title') ?></h1>
    <p class="lead reveal" style="--d:200ms"><?= t('hero_sub') ?></p>
    <div class="btns reveal" style="--d:300ms">
      <?php if ($loggedIn): ?>
      <a class="btn btn--primary" href="destinations.php"><?= t('hero_cta1') ?> →</a>
      <a class="btn btn--ghost" href="booking.php"><?= t('hero_cta2') ?></a>
      <?php else: ?>
      <a class="btn btn--primary" href="register.php"><?= t('signup_now') ?> →</a>
      <a class="btn btn--ghost" href="login.php"><?= t('nav_login') ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="wrap">
  <div class="stats">
    <div class="stat reveal"><b data-count="50" data-suffix="+">0</b><span><?= t('stat1') ?></span></div>
    <div class="stat reveal"><b data-count="12000" data-suffix="+">0</b><span><?= t('stat2') ?></span></div>
    <div class="stat reveal"><b data-count="10" data-suffix="+">0</b><span><?= t('stat3') ?></span></div>
    <div class="stat reveal"><b>24/7</b><span><?= t('stat4') ?></span></div>
  </div>
</div>

<section class="section wrap">
  <div class="cards3">
    <article class="card tilt reveal"><div class="icon">🧭</div><h3><?= t('f1_t') ?></h3><p><?= t('f1_d') ?></p></article>
    <article class="card tilt reveal"><div class="icon">🛡️</div><h3><?= t('f2_t') ?></h3><p><?= t('f2_d') ?></p></article>
    <article class="card tilt reveal"><div class="icon">🤝</div><h3><?= t('f3_t') ?></h3><p><?= t('f3_d') ?></p></article>
  </div>
</section>

<section class="section wrap" style="padding-top:0">
  <div class="about">
    <div class="about__img reveal"><img src="img/Dubrovnik.jpg" alt="Dubrovnik" width="800" height="1000"></div>
    <div class="about__txt">
      <div class="sec-head reveal" style="text-align:left;margin-bottom:1.5rem"><h2><?= t('about_title') ?></h2><p style="margin-inline:0"><?= t('about_sub') ?></p></div>
      <p class="reveal"><?= t('about_p1') ?></p>
      <p class="reveal"><?= t('about_p2') ?></p>
      <p class="reveal"><?= t('about_p3') ?></p>
      <p class="reveal"><?= t('about_p4') ?></p>
    </div>
  </div>
</section>

<div class="wrap">
  <div class="cta reveal">
    <h2><?= t('cta_title') ?></h2>
    <?php if ($loggedIn): ?>
    <p><?= t('cta_sub') ?></p>
    <a class="btn" href="booking.php"><?= t('hero_cta2') ?> →</a>
    <?php else: ?>
    <p><?= t('cta_guest_sub') ?></p>
    <a class="btn" href="register.php"><?= t('signup_now') ?> →</a>
    <?php endif; ?>
  </div>
</div>

<div class="offer" id="offer"><span><?= t('offer') ?></span> <a href="<?= $loggedIn ? 'booking.php' : 'register.php' ?>"><?= $loggedIn ? t('offer_cta') : t('signup_now') ?></a><button aria-label="<?= h(t('close')) ?>">&times;</button></div>
<?php include 'includes/footer.php'; ?>
