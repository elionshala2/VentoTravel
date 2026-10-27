<?php
// Expects: $page (nav key), $seo_t, $seo_d (lang keys), $mode: 'public' | 'user' | 'admin'
$mode = $mode ?? 'user';
$page = $page ?? '';
$base = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . strtok($_SERVER['REQUEST_URI'], '?');
if ($mode === 'admin') {
    $links = ['admin.php'=>['nav_admin','admin'],'admin-destinations.php'=>['nav_adm_dest','adm_dest'],'admin-bookings.php'=>['nav_adm_book','adm_book'],'admin-feedbacks.php'=>['nav_adm_fb','adm_fb'],'admin-faq.php'=>['nav_adm_faq','adm_faq']];
} elseif ($mode === 'guest') {
    $links = ['index.php'=>['nav_about','home'],'login.php'=>['nav_login','login'],'register.php'=>['nav_register','reg']];
} elseif ($mode === 'user') {
    $links = ['index.php'=>['nav_about','home'],'destinations.php'=>['nav_destinations','dest'],'booking.php'=>['nav_book','book'],'feedback.php'=>['nav_feedback','fb'],'faq.php'=>['nav_faq','faq'],'history.php'=>['nav_history','hist']];
} else {
    $links = ['index.php'=>['nav_about','home'],'login.php'=>['nav_login','login'],'register.php'=>['nav_register','reg']];
}
?>
<!DOCTYPE html>
<html lang="<?= h($LANG) ?>" data-theme="<?= h($THEME) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h(t($seo_t)) ?></title>
<meta name="description" content="<?= h(t($seo_d)) ?>">
<meta name="author" content="VentoTravel">
<meta name="robots" content="<?= $mode === 'admin' ? 'noindex,nofollow' : 'index,follow' ?>">
<meta name="theme-color" content="<?= $THEME === 'light' ? '#f4f7fc' : '#0b1220' ?>">
<link rel="canonical" href="<?= h($base) ?>">
<link rel="alternate" hreflang="en" href="<?= h($base) ?>?lang=en">
<link rel="alternate" hreflang="sq" href="<?= h($base) ?>?lang=sq">
<link rel="alternate" hreflang="x-default" href="<?= h($base) ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="VentoTravel">
<meta property="og:title" content="<?= h(t($seo_t)) ?>">
<meta property="og:description" content="<?= h(t($seo_d)) ?>">
<meta property="og:image" content="<?= h(dirname($base)) ?>/img/bck.jpg">
<meta property="og:locale" content="<?= $LANG === 'sq' ? 'sq_AL' : 'en_US' ?>">
<meta name="twitter:card" content="summary_large_image">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<?php if ($page === 'home'): ?>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"TravelAgency","name":"VentoTravel","description":"<?= h(t('seo_home_d')) ?>","url":"<?= h($base) ?>"}</script>
<?php endif; ?>
</head>
<body class="<?= h($mode) ?>-mode">
<a class="skip" href="#main"><?= h(t('skip')) ?></a>
<div class="scroll-progress" aria-hidden="true"></div>
<header class="nav" id="nav">
  <div class="nav__inner">
    <a class="brand" href="<?= $mode === 'admin' ? 'admin.php' : 'index.php' ?>" aria-label="VentoTravel">
      <svg viewBox="0 0 32 32" width="30" height="30" aria-hidden="true"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#22d3ee"/><stop offset="1" stop-color="#fb7185"/></linearGradient></defs><path d="M3 16 29 4 21 28l-4-9-14-3z" fill="url(#g)"/></svg>
      <span>Vento<b>Travel</b></span>
    </a>
    <nav class="nav__links" id="navLinks" aria-label="Main">
      <?php foreach ($links as $href => [$k, $id]): ?>
        <a href="<?= $href ?>" class="<?= $page === $id ? 'is-active' : '' ?>" <?= $page === $id ? 'aria-current="page"' : '' ?>><?= h(t($k)) ?></a>
      <?php endforeach; ?>
      <?php if ($mode === 'user' || $mode === 'admin'): ?>
        <a href="logout.php" class="nav__logout"><?= h(t('nav_logout')) ?></a>
      <?php endif; ?>
    </nav>
    <div class="nav__actions">
      <?php if ($mode !== 'public' && isset($_SESSION['username'])): ?>
        <a class="user-chip" href="<?= $mode === 'admin' ? 'admin.php' : 'account.php' ?>" title="<?= h(t('account_title')) ?>"><i><?= h(strtoupper(mb_substr($_SESSION['username'], 0, 1))) ?></i><span><?= h($_SESSION['username']) ?></span></a>
      <?php endif; ?>
      <div class="lang" role="group" aria-label="<?= h(t('switch_lang')) ?>">
        <a href="<?= h(lang_url('en')) ?>" class="<?= $LANG === 'en' ? 'on' : '' ?>" hreflang="en" lang="en" title="English">EN</a>
        <a href="<?= h(lang_url('sq')) ?>" class="<?= $LANG === 'sq' ? 'on' : '' ?>" hreflang="sq" lang="sq" title="Shqip">AL</a>
        <span class="lang__thumb" aria-hidden="true"></span>
      </div>
      <button class="theme-btn" id="themeBtn" aria-label="Toggle theme" title="Dark / Light"><svg class="i-sun" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg><svg class="i-moon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg></button>
      <button class="burger" id="burger" aria-label="<?= h(t('menu')) ?>" aria-expanded="false" aria-controls="navLinks"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav__backdrop" id="navBackdrop"></div>
<main id="main">
