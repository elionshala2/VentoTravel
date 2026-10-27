<?php
// Expects $with_passkey (bool). Shows only providers that are configured in .env.
$sg = google_enabled(); $sa = apple_enabled(); $sp = !empty($with_passkey) && passkeys_enabled();
if ($sg || $sa || $sp): ?>
<div class="divider" role="separator"><span><?= h(t('or_continue')) ?></span></div>
<div class="social">
  <?php if ($sp): ?>
  <button type="button" class="sbtn" id="passkeyLogin" hidden data-csrf="<?= h(csrf_token()) ?>" data-err="<?= h(t('err_passkey')) ?>">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="8" cy="15" r="4"/><path d="M10.8 12.2 20 3m-3 3 3 3m-6-0 2 2"/></svg>
    <?= h(t('btn_passkey')) ?></button>
  <?php endif; ?>
  <?php if ($sg): ?>
  <a class="sbtn" href="auth/google.php">
    <svg viewBox="0 0 48 48" width="20" height="20" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.6l6.8-6.8C35.8 2.4 30.3 0 24 0 14.6 0 6.5 5.4 2.6 13.2l7.9 6.1C12.4 13.6 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v9h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8c4.4-4.1 7.1-10.1 7.1-17.5z"/><path fill="#FBBC05" d="M10.5 28.7a14.500 14.500 0 0 1 0-9.4l-7.9-6.1a24 24 0 0 0 0 21.6l7.9-6.1z"/><path fill="#34A853" d="M24 48c6.5 0 11.900-2.100 15.900-5.800l-7.500-5.800c-2.100 1.400-4.800 2.300-8.400 2.300-6.300 0-11.600-4.100-13.500-9.800l-7.900 6.100C6.500 42.600 14.600 48 24 48z"/></svg>
    <?= h(t('btn_google')) ?></a>
  <?php endif; ?>
  <?php if ($sa): ?>
  <a class="sbtn" href="auth/apple.php">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M16.4 12.6c0-2.500 2-3.700 2.100-3.800-1.200-1.700-3-1.900-3.600-2-1.500-.2-3 .9-3.800.9-.8 0-2-.9-3.300-.9-1.700 0-3.300 1-4.200 2.500-1.800 3.100-.5 7.700 1.300 10.200.9 1.200 1.900 2.600 3.200 2.500 1.300-.1 1.800-.8 3.300-.8s2 .8 3.300.8c1.400 0 2.300-1.200 3.100-2.400 1-1.400 1.400-2.800 1.400-2.900-.1 0-2.800-1.100-2.800-4.100zM13.900 5.200c.7-.9 1.200-2.100 1.100-3.300-1 0-2.300.7-3 1.600-.7.800-1.300 2-1.100 3.200 1.200.1 2.300-.6 3-1.500z"/></svg>
    <?= h(t('btn_apple')) ?></a>
  <?php endif; ?>
</div>
<p class="form-msg" id="authMsg" role="alert" hidden></p>
<?php endif; ?>
