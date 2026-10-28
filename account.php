<?php
require_once 'includes/security.php';
vt_session_start(); vt_security_headers();
include 'config.php';
include 'includes/lang.php';
require_once 'includes/auth_lib.php';
require_login();
require_once 'includes/account_lib.php';
if (($_SESSION['username'] ?? '') === 'admin' || empty($_SESSION['id'])) { header('Location: admin.php'); exit; }
$uid = (int)$_SESSION['id'];

function load_user(mysqli $c, int $uid): array {
    $st = $c->prepare('SELECT username, email, password, has_password, twofa_enabled FROM regjistrimi WHERE id=?'); $st->bind_param('i', $uid); $st->execute();
    return $st->get_result()->fetch_assoc() ?: [];
}
function pw_ok(array $u, string $pw): bool {
    $s = (string)$u['password'];
    if (preg_match('/^[a-f0-9]{32}$/i', $s)) return hash_equals(strtolower($s), md5($pw));
    return password_verify($pw, $s);
}
function kill_other_sessions(mysqli $c, int $uid): void {
    $h = hash('sha256', session_id());
    $d = $c->prepare('DELETE FROM user_sessions WHERE user_id=? AND sid_hash<>?'); $d->bind_param('is', $uid, $h); $d->execute();
}

$U = load_user($conn, $uid);
$hasPw = !empty($U['has_password']);
$pending = $_SESSION['acc_pending'] ?? '';   // purpose waiting for an emailed code
$msg = ''; $type = 'ok';
$act = ($_SERVER['REQUEST_METHOD'] === 'POST') ? (string)($_POST['action'] ?? '') : '';

if ($act !== '') {
    if (!csrf_valid()) { $msg = t('err_csrf'); $type = 'err'; }
    elseif (rl_count("acc|$uid", 600) >= 40) { $msg = t('err_rate'); $type = 'err'; }
    else {
        rl_hit("acc|$uid", 600);
        $err = function (string $m) use (&$msg, &$type) { $msg = $m; $type = 'err'; };
        switch ($act) {
        case 'username':
            $n = preg_replace('/\s+/u', ' ', trim((string)($_POST['username'] ?? '')));
            if (mb_strlen($n) < 3 || mb_strlen($n) > 30 || !preg_match('/^[\p{L}\p{N}_. -]+$/u', $n)) $err(t('err_user_fmt'));
            elseif (strtolower($n) === 'admin') $err(t('err_user_admin'));
            elseif ($n === $U['username']) $err('That is already your username.');
            else {
                $c = $conn->prepare('SELECT id FROM regjistrimi WHERE username=? AND id<>? LIMIT 1'); $c->bind_param('si', $n, $uid); $c->execute();
                if ($c->get_result()->num_rows) $err('That username is already taken.');
                else {
                    try { $u = $conn->prepare('UPDATE regjistrimi SET username=? WHERE id=?'); $u->bind_param('si', $n, $uid); $u->execute(); $_SESSION['username'] = $n; $msg = 'Username updated.'; }
                    catch (Throwable $e) { $err('That username is already taken.'); }
                }
            }
            break;

        case 'password':
            $new = (string)($_POST['new_password'] ?? '');
            if ($hasPw && !pw_ok($U, (string)($_POST['current_password'] ?? ''))) $err('Current password is incorrect.');
            elseif (strlen($new) < 8 || strlen($new) > 72) $err(t('err_pw_len'));
            elseif (!hash_equals($new, (string)($_POST['confirm_password'] ?? ''))) $err(t('err_pw'));
            else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $u = $conn->prepare('UPDATE regjistrimi SET password=?, has_password=1 WHERE id=?'); $u->bind_param('si', $hash, $uid); $u->execute();
                kill_other_sessions($conn, $uid);
                $msg = $hasPw ? 'Password changed. Other sessions were signed out.' : 'Password created.';
            }
            break;

        case 'remove_passkey':
            $d = $conn->prepare('DELETE FROM passkeys WHERE id=? AND user_id=?'); $pid = (int)($_POST['pid'] ?? 0); $d->bind_param('ii', $pid, $uid); $d->execute();
            $msg = t('ok_removed'); break;

        case 'close_sessions':
            kill_other_sessions($conn, $uid); $msg = 'All other sessions were closed.'; break;

        case 'code_send':   // twofa_on | twofa_off | delete
            $p = (string)($_POST['purpose'] ?? '');
            if (!in_array($p, ['twofa_on', 'twofa_off', 'delete'], true)) { $err(t('err_generic')); break; }
            if ($p === 'twofa_on' && !empty($U['twofa_enabled'])) { $err('Two-factor is already on.'); break; }
            if ($p === 'twofa_off' && empty($U['twofa_enabled'])) { $err('Two-factor is already off.'); break; }
            if ($p === 'delete') {   // identity check first
                if ($hasPw) { if (!pw_ok($U, (string)($_POST['confirm'] ?? ''))) { $err('Password is incorrect.'); break; } }
                elseif (!hash_equals((string)$U['username'], (string)($_POST['confirm'] ?? ''))) { $err('Type your username exactly to confirm.'); break; }
            }
            if (vt_code_send($conn, $uid, $p, $U['email'], $U['username'])) { $_SESSION['acc_pending'] = $pending = $p; $msg = 'We emailed you a 6-digit code (valid 10 minutes).'; }
            else $err('Could not send the email: ' . ($GLOBALS['VT_MAIL_ERR'] ?: 'too many requests, try again later') );
            break;

        case 'code_confirm':
            $p = (string)($_POST['purpose'] ?? '');
            if ($p !== $pending || !vt_code_check($conn, $uid, $p, (string)($_POST['code'] ?? ''))) { $err('Invalid or expired code.'); break; }
            unset($_SESSION['acc_pending']); $pending = '';
            if ($p === 'twofa_on' || $p === 'twofa_off') {
                $v = $p === 'twofa_on' ? 1 : 0;
                $u = $conn->prepare('UPDATE regjistrimi SET twofa_enabled=? WHERE id=?'); $u->bind_param('ii', $v, $uid); $u->execute();
                $msg = $v ? 'Two-factor authentication is now ON.' : 'Two-factor authentication is now OFF.';
            } elseif ($p === 'delete') {
                $conn->begin_transaction();
                foreach (['rezervimet', 'feedbacks'] as $tb) { $d = $conn->prepare("DELETE FROM `$tb` WHERE user_id=?"); $d->bind_param('i', $uid); $d->execute(); }
                $d = $conn->prepare('DELETE FROM regjistrimi WHERE id=?'); $d->bind_param('i', $uid); $d->execute();
                $conn->commit();
                $_SESSION = []; session_destroy();
                header('Location: index.php'); exit;
            }
            break;

        case 'code_cancel': unset($_SESSION['acc_pending']); $pending = ''; break;
        }
        $U = load_user($conn, $uid); $hasPw = !empty($U['has_password']);
    }
}
$g = $_GET['glink'] ?? '';
if ($g === 'ok') { $msg = 'Google account connected. Your account email is now ' . $U['email'] . '.'; } elseif ($g === 'emailtaken') { $msg = 'That Google email is already used by another account, so it can\'t be connected.'; $type = 'err'; } elseif ($g === 'taken') { $msg = 'That Google account is already linked to another user.'; $type = 'err'; }
if ($msg !== '') flash($msg, $type);

$st = $conn->prepare('SELECT id, name, created_at, last_used_at FROM passkeys WHERE user_id=? ORDER BY id DESC'); $st->bind_param('i', $uid); $st->execute(); $keys = $st->get_result()->fetch_all(MYSQLI_ASSOC);
$st = $conn->prepare('SELECT provider FROM oauth_accounts WHERE user_id=?'); $st->bind_param('i', $uid); $st->execute();
$linked = array_column($st->get_result()->fetch_all(MYSQLI_ASSOC), 'provider');
$cur = hash('sha256', session_id());
$sessions = [];
try { $st = $conn->prepare('SELECT sid_hash, ip, user_agent, created_at, last_seen FROM user_sessions WHERE user_id=? ORDER BY last_seen DESC'); $st->bind_param('i', $uid); $st->execute(); $sessions = $st->get_result()->fetch_all(MYSQLI_ASSOC); } catch (Throwable $e) { error_log('sessions: ' . $e->getMessage()); }
$tf = !empty($U['twofa_enabled']);

$mode = 'user'; $page = 'account'; $seo_t = 'seo_account_t'; $seo_d = 'seo_account_d';
include 'includes/header.php';

function code_form(string $p): void { ?>
  <form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="purpose" value="<?= h($p) ?>">
    <div class="field"><label>Verification code</label><input name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required></div>
    <div class="btnrow" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:center"><button class="btn btn--primary" name="action" value="code_confirm">Confirm</button>
    <button class="btn btn--ghost" name="action" value="code_cancel" formnovalidate>Cancel</button></div>
  </form>
<?php } ?>
<section class="acct">
  <h1><?= h(t('account_title')) ?></h1>
  <p class="sub"><?= h($U['username']) ?> · <?= h($U['email']) ?></p>

  <div class="acct__card">
    <h2>Username</h2>
    <form method="post" class="stack"><?= csrf_field() ?><input type="hidden" name="action" value="username">
      <div class="field"><input name="username" value="<?= h($U['username']) ?>" minlength="3" maxlength="30" required></div>
      <button class="btn btn--primary" type="submit">Save username</button>
    </form>
  </div>

  <div class="acct__card">
    <h2><?= $hasPw ? 'Change password' : 'Create a password' ?></h2>
    <?php if (!$hasPw): ?><p class="sub">Your account was created with Google, so it has no password yet. Create one to also sign in with your email.</p><?php endif; ?>
    <form method="post" class="stack" autocomplete="off"><?= csrf_field() ?><input type="hidden" name="action" value="password">
      <?php if ($hasPw): ?><div class="field"><label>Current password</label><input type="password" name="current_password" maxlength="72" autocomplete="current-password" required></div><?php endif; ?>
      <div class="field"><label>New password</label><input type="password" name="new_password" minlength="8" maxlength="72" autocomplete="new-password" required></div>
      <div class="field"><label>Confirm new password</label><input type="password" name="confirm_password" minlength="8" maxlength="72" autocomplete="new-password" required></div>
      <button class="btn btn--primary" type="submit"><?= $hasPw ? 'Change password' : 'Create password' ?></button>
    </form>
  </div>

  <div class="acct__card">
    <h2>Two-factor authentication <?= $tf ? '· ON' : '· OFF' ?></h2>
    <p class="sub">When on, signing in with your password also needs a 6-digit code emailed to <?= h($U['email']) ?>. (Passkeys and Google sign-in skip it.)</p>
    <?php $pp = $tf ? 'twofa_off' : 'twofa_on'; if ($pending === $pp) code_form($pp); else { ?>
    <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="code_send"><input type="hidden" name="purpose" value="<?= $pp ?>">
      <button class="btn btn--primary" type="submit"><?= $tf ? 'Turn off 2FA' : 'Turn on 2FA' ?></button></form>
    <?php } ?>
  </div>

  <div class="acct__card">
    <h2><?= h(t('acc_passkeys')) ?></h2>
    <p class="sub"><?= h(t('acc_passkeys_d')) ?></p>
    <?php if (!$keys): ?><p class="empty"><?= h(t('acc_none')) ?></p><?php endif; ?>
    <ul class="keylist">
      <?php foreach ($keys as $k): ?>
      <li>
        <div><strong><?= h($k['name']) ?></strong>
          <span><?= h(t('acc_added')) ?> <?= h(date('Y-m-d', strtotime($k['created_at']))) ?> · <?= h(t('acc_last')) ?> <?= $k['last_used_at'] ? h(date('Y-m-d', strtotime($k['last_used_at']))) : h(t('acc_never')) ?></span></div>
        <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="remove_passkey"><input type="hidden" name="pid" value="<?= (int)$k['id'] ?>">
          <button class="btn btn--ghost btn--sm" type="submit"><?= h(t('acc_remove')) ?></button></form>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php if (passkeys_enabled()): ?>
    <div class="field"><label for="passkeyName">Name for the new passkey (optional)</label><input id="passkeyName" maxlength="60" placeholder="e.g. iPhone, Work laptop"></div>
    <button type="button" class="btn btn--primary" id="passkeyAdd" data-csrf="<?= h(csrf_token()) ?>" data-err="<?= h(t('err_passkey')) ?>"><?= h(t('acc_add_passkey')) ?></button>
    <p class="form-msg" id="authMsg" role="alert" hidden></p>
    <?php endif; ?>
  </div>

  <div class="acct__card">
    <h2>Sessions</h2>
    <ul class="keylist">
      <?php foreach ($sessions as $s): $me = hash_equals($s['sid_hash'], $cur); ?>
      <li><div><strong><?= h(mb_substr($s['user_agent'] ?: 'Unknown device', 0, 70)) ?><?= $me ? ' · this device' : '' ?></strong>
        <span><?= h($s['ip']) ?> · signed in <?= h(date('Y-m-d H:i', strtotime($s['created_at']))) ?> · active <?= h(date('Y-m-d H:i', strtotime($s['last_seen']))) ?></span></div></li>
      <?php endforeach; ?>
    </ul>
    <form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="close_sessions"><button class="btn btn--ghost" type="submit">Close all other sessions</button></form>
  </div>

  <div class="acct__card">
    <h2>Connected accounts</h2>
    <?php if (in_array('google', $linked, true)): ?><ul class="keylist"><li><strong>Google</strong><span>Connected</span></li></ul>
    <?php elseif (google_enabled()): ?><a class="btn btn--primary" href="auth/google-connect.php">Connect Google</a>
    <?php else: ?><p class="empty">Google sign-in is not configured.</p><?php endif; ?>
  </div>

  <div class="acct__card">
    <h2>Delete account</h2>
    <p class="sub">This permanently deletes your account, bookings and feedback. We'll email you a code to confirm.</p>
    <?php if ($pending === 'delete') code_form('delete'); else { ?>
    <form method="post" class="stack" autocomplete="off"><?= csrf_field() ?><input type="hidden" name="action" value="code_send"><input type="hidden" name="purpose" value="delete">
      <div class="field"><label><?= $hasPw ? 'Enter your password' : 'Type your username (' . h($U['username']) . ') to confirm' ?></label>
        <input name="confirm" <?= $hasPw ? 'type="password" maxlength="72" autocomplete="current-password"' : 'type="text" maxlength="60"' ?> required></div>
      <button class="btn btn--primary" type="submit" style="background:#e11d48">Send code &amp; delete…</button>
    </form>
    <?php } ?>
  </div>
</section>
<script src="js/passkey.js" defer></script>
<?php include 'includes/footer.php'; ?>
