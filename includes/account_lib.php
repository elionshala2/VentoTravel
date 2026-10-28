<?php
require_once __DIR__ . '/mailer.php';

function vt_track_session(mysqli $conn, int $uid): void {
    try {
        $h = hash('sha256', session_id()); $ip = substr(client_ip(), 0, 45); $ua = substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
        $st = $conn->prepare('INSERT INTO user_sessions (user_id, sid_hash, ip, user_agent) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE user_id=VALUES(user_id)');
        $st->bind_param('isss', $uid, $h, $ip, $ua); $st->execute();
    } catch (Throwable $e) { error_log('track: ' . $e->getMessage()); }
}
/** Called on every protected page: kills sessions that were closed from another device. */
function vt_session_valid(): void {
    global $conn;
    $uid = (int)($_SESSION['id'] ?? 0);
    if ($uid <= 0 || !isset($conn)) return;
    try {
        $h = hash('sha256', session_id());
        $st = $conn->prepare('SELECT id FROM user_sessions WHERE sid_hash=? AND user_id=? LIMIT 1');
        $st->bind_param('si', $h, $uid); $st->execute();
        if (!$st->get_result()->fetch_assoc()) { $_SESSION = []; session_destroy(); header('Location: login.php'); exit; }
        if (time() - (int)($_SESSION['seen'] ?? 0) > 60) {
            $_SESSION['seen'] = time();
            $u = $conn->prepare('UPDATE user_sessions SET last_seen=NOW() WHERE sid_hash=?'); $u->bind_param('s', $h); $u->execute();
        }
    } catch (Throwable $e) { /* migration not run yet: don't lock users out */ }
}
function vt_code_send(mysqli $conn, int $uid, string $purpose, string $email, string $username): bool {
    if (rl_count("code|$uid", 3600) >= 6) return false;
    rl_hit("code|$uid", 3600);
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $hash = hash_hmac('sha256', $code, 'vt|' . $uid . '|' . $purpose);
    $d = $conn->prepare('DELETE FROM email_codes WHERE user_id=? AND purpose=?'); $d->bind_param('is', $uid, $purpose); $d->execute();
    $i = $conn->prepare('INSERT INTO email_codes (user_id, purpose, code_hash, expires_at) VALUES (?,?,?,DATE_ADD(NOW(), INTERVAL 10 MINUTE))');
    $i->bind_param('iss', $uid, $purpose, $hash); $i->execute();
    $what = ['login' => 'sign in', 'twofa_on' => 'turn on two-factor authentication', 'twofa_off' => 'turn off two-factor authentication', 'delete' => 'DELETE your account'][$purpose] ?? 'confirm an action';
    $e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    $titles = ['login' => 'Your sign-in code', 'twofa_on' => 'Turn on two-factor', 'twofa_off' => 'Turn off two-factor', 'delete' => 'Confirm account deletion'];
    $title = $titles[$purpose] ?? 'Your verification code';
    $warn = $purpose === 'delete' ? '<p style="margin:0 0 16px;font-size:14px;color:#dc2626;font-weight:600">This will permanently delete your account, bookings and feedback.</p>' : '';
    $url = app_url() . ($purpose === 'login' ? '/login.php' : '/account.php');
    $btn = $purpose === 'login' ? 'Back to sign in' : 'Open my account';
    $html = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f3f4f6">'
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 12px"><tr><td align="center">'
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:20px;padding:36px;font-family:Arial,Helvetica,sans-serif;color:#374151"><tr><td>'
      . '<div style="font-size:26px;font-weight:700;color:#111827;margin-bottom:24px">Vento<span style="color:#0891b2">Travel</span></div>'
      . '<h1 style="margin:0 0 12px;font-size:22px;color:#111827">' . $e($title) . '</h1>'
      . '<p style="margin:0 0 20px;font-size:16px;line-height:1.5">Hi ' . $e($username) . ', use this code to ' . $e($what) . ':</p>' . $warn
      . '<div style="margin:0 0 24px;padding:18px 0;text-align:center;background:#f3f4f6;border-radius:14px;font-size:34px;letter-spacing:10px;font-weight:700;color:#111827;font-family:Consolas,Menlo,monospace">' . $code . '</div>'
      . '<a href="' . $e($url) . '" style="display:inline-block;background:#3b82f6;color:#ffffff;text-decoration:none;font-weight:700;font-size:16px;padding:15px 28px;border-radius:12px">' . $e($btn) . '</a>'
      . '<p style="margin:28px 0 0;font-size:13px;line-height:1.5;color:#6b7280">This code expires in 10 minutes. If you didn\'t request it, you can ignore this email' . ($purpose === 'login' ? ' and consider changing your password' : '') . '. Never share this code with anyone.</p>'
      . '</td></tr></table></td></tr></table></body></html>';
    $text = "Hi $username,\n\nYour verification code to $what is:\n\n    $code\n\nIt expires in 10 minutes. If you didn't request this, ignore this email.\n\n— VentoTravel";
    return vt_mail($email, 'VentoTravel: ' . $title, $text, $html);
}
function vt_code_check(mysqli $conn, int $uid, string $purpose, string $code): bool {
    $code = preg_replace('/\D/', '', $code);
    $st = $conn->prepare('SELECT id, code_hash, attempts FROM email_codes WHERE user_id=? AND purpose=? AND expires_at > NOW() LIMIT 1');
    $st->bind_param('is', $uid, $purpose); $st->execute();
    $r = $st->get_result()->fetch_assoc();
    if (!$r) return false;
    if ((int)$r['attempts'] >= 5) { $d = $conn->prepare('DELETE FROM email_codes WHERE id=?'); $d->bind_param('i', $r['id']); $d->execute(); return false; }
    if (strlen($code) === 6 && hash_equals($r['code_hash'], hash_hmac('sha256', $code, 'vt|' . $uid . '|' . $purpose))) {
        $d = $conn->prepare('DELETE FROM email_codes WHERE id=?'); $d->bind_param('i', $r['id']); $d->execute(); return true;
    }
    $u = $conn->prepare('UPDATE email_codes SET attempts=attempts+1 WHERE id=?'); $u->bind_param('i', $r['id']); $u->execute();
    return false;
}

/** Sign-up email verification: store the pending account (not a real user yet) and email a code. */
function vt_signup_start(mysqli $conn, string $username, string $email, string $pwHash): bool {
    $conn->query('DELETE FROM pending_signups WHERE expires_at < NOW()');
    $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $ch = hash_hmac('sha256', $code, 'signup|' . strtolower($email));
    $d = $conn->prepare('DELETE FROM pending_signups WHERE email=?'); $d->bind_param('s', $email); $d->execute();
    $i = $conn->prepare('INSERT INTO pending_signups (email, username, password_hash, code_hash, expires_at) VALUES (?,?,?,?,DATE_ADD(NOW(), INTERVAL 15 MINUTE))');
    $i->bind_param('ssss', $email, $username, $pwHash, $ch); $i->execute();
    $e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    $html = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f3f4f6">'
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 12px"><tr><td align="center">'
      . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:20px;padding:36px;font-family:Arial,Helvetica,sans-serif;color:#374151"><tr><td>'
      . '<div style="font-size:26px;font-weight:700;color:#111827;margin-bottom:24px">Vento<span style="color:#0891b2">Travel</span></div>'
      . '<h1 style="margin:0 0 12px;font-size:22px;color:#111827">Verify your email</h1>'
      . '<p style="margin:0 0 20px;font-size:16px;line-height:1.5">Hi ' . $e($username) . ', welcome! Enter this code to finish creating your account:</p>'
      . '<div style="margin:0 0 24px;padding:18px 0;text-align:center;background:#f3f4f6;border-radius:14px;font-size:34px;letter-spacing:10px;font-weight:700;color:#111827;font-family:Consolas,Menlo,monospace">' . $code . '</div>'
      . '<a href="' . $e(app_url() . '/verify-email.php') . '" style="display:inline-block;background:#3b82f6;color:#ffffff;text-decoration:none;font-weight:700;font-size:16px;padding:15px 28px;border-radius:12px">Enter code</a>'
      . '<p style="margin:28px 0 0;font-size:13px;line-height:1.5;color:#6b7280">This code expires in 15 minutes. If you didn\'t sign up for VentoTravel, you can safely ignore this email &mdash; no account will be created.</p>'
      . '</td></tr></table></td></tr></table></body></html>';
    return vt_mail($email, 'VentoTravel: Verify your email', "Hi $username,\n\nYour VentoTravel verification code is:\n\n    $code\n\nIt expires in 15 minutes. If you didn't sign up, ignore this email.", $html);
}
/** Returns the new user id on success, or null. */
function vt_signup_finish(mysqli $conn, string $email, string $code): ?array {
    $code = preg_replace('/\D/', '', $code);
    $st = $conn->prepare('SELECT id, username, password_hash, code_hash, attempts FROM pending_signups WHERE email=? AND expires_at > NOW() LIMIT 1');
    $st->bind_param('s', $email); $st->execute();
    $r = $st->get_result()->fetch_assoc();
    if (!$r) return null;
    if ((int)$r['attempts'] >= 5) { $d = $conn->prepare('DELETE FROM pending_signups WHERE id=?'); $d->bind_param('i', $r['id']); $d->execute(); return null; }
    if (strlen($code) !== 6 || !hash_equals($r['code_hash'], hash_hmac('sha256', $code, 'signup|' . strtolower($email)))) {
        $u = $conn->prepare('UPDATE pending_signups SET attempts=attempts+1 WHERE id=?'); $u->bind_param('i', $r['id']); $u->execute(); return null;
    }
    $c = $conn->prepare('SELECT id FROM regjistrimi WHERE email=? OR username=? LIMIT 1'); $c->bind_param('ss', $email, $r['username']); $c->execute();
    if ($c->get_result()->num_rows) { $d = $conn->prepare('DELETE FROM pending_signups WHERE id=?'); $d->bind_param('i', $r['id']); $d->execute(); return ['error' => 'taken']; }
    $i = $conn->prepare('INSERT INTO regjistrimi (username, email, password) VALUES (?,?,?)');
    $i->bind_param('sss', $r['username'], $email, $r['password_hash']);
    if (!$i->execute()) return null;
    $uid = (int)$conn->insert_id;
    $d = $conn->prepare('DELETE FROM pending_signups WHERE id=?'); $d->bind_param('i', $r['id']); $d->execute();
    return ['id' => $uid, 'username' => $r['username']];
}
