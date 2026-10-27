</main>
<footer class="footer">
  <div class="footer__inner">
    <div><strong>VentoTravel</strong><p><?= h(t('footer_tag')) ?></p></div>
    <div class="footer__right">
      <nav class="footer__links" aria-label="Legal"><a href="privacy.php"><?= h(t('legal_privacy')) ?></a><a href="terms.php"><?= h(t('legal_terms')) ?></a></nav>
      <p>© <?= date('Y') ?> VentoTravel. <?= h(t('footer_rights')) ?></p>
    </div>
  </div>
</footer>
<?php if (!empty($FLASH)): ?>
<div class="toast toast--<?= h($FLASH['type']) ?>" role="status"><?= h($FLASH['msg']) ?><button aria-label="<?= h(t('close')) ?>">&times;</button></div>
<?php endif; ?>
<script src="js/app.js" defer></script>
</body>
</html>
