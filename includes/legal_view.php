<?php
// Expects $doc = 'privacy' | 'terms'
$__all = require __DIR__ . '/legal_content.php';
$__d = $__all[$LANG][$doc] ?? $__all['en'][$doc];
$__email = env('CONTACT_EMAIL', 'info@ventotravel.example');
$mode = !isset($_SESSION['username']) ? 'guest' : (($_SESSION['username'] === 'admin') ? 'admin' : 'user');
$page = $doc; $seo_t = 'seo_' . $doc . '_t'; $seo_d = 'seo_' . $doc . '_d';
include __DIR__ . '/header.php';
?>
<article class="legal">
  <h1><?= h($__d['title']) ?></h1>
  <p class="legal__date"><?= h(t('legal_updated')) ?>: 2026-10-27</p>
  <?php foreach ($__d['sections'] as $i => [$hd, $tx]): ?>
  <section>
    <h2><?= $i + 1 ?>. <?= h($hd) ?></h2>
    <p><?= h(str_replace('{email}', $__email, $tx)) ?></p>
  </section>
  <?php endforeach; ?>
  <p class="legal__links"><a href="<?= $doc === 'privacy' ? 'terms.php' : 'privacy.php' ?>"><?= h(t($doc === 'privacy' ? 'legal_terms' : 'legal_privacy')) ?> →</a></p>
</article>
<?php include __DIR__ . '/footer.php'; ?>
