</main>
<footer class="footer">
  <div class="footer__inner">
    <div><strong>VentoTravel</strong><p><?= h(t('footer_tag')) ?></p></div>
    <p>© <?= date('Y') ?> VentoTravel. <?= h(t('footer_rights')) ?></p>
  </div>
</footer>
<?php if (!empty($FLASH)): ?>
<div class="toast toast--<?= h($FLASH['type']) ?>" role="status"><?= h($FLASH['msg']) ?><button aria-label="<?= h(t('close')) ?>">&times;</button></div>
<?php endif; ?>
<script src="js/app.js" defer></script>
</body>
</html>
