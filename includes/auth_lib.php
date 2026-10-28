<?php
// Shared helpers for Google / Apple / passkey sign-in.

function app_url(): string {
    $u = env('APP_URL');
    if ($u) return rtrim($u, '/');
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    return ($https ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
}
function google_enabled(): bool { return env('GOOGLE_CLIENT_ID') && env('GOOGLE_CLIENT_SECRET') && class_exists('League\OAuth2\Client\Provider\Google'); }
function apple_enabled(): bool { return env('APPLE_CLIENT_ID') && env('APPLE_TEAM_ID') && env('APPLE_KEY_ID') && env('APPLE_PRIVATE_KEY_PATH') && class_exists('Firebase\JWT\JWT'); }
function passkeys_enabled(): bool { return class_exists('lbuchs\WebAuthn\WebAuthn'); }

function vt_google_provider(): \League\OAuth2\Client\Provider\Google {
    return new \League\OAuth2\Client\Provider\Google([
        'clientId' => env('GOOGLE_CLIENT_ID'), 'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
        'redirectUri' => app_url() . '/auth/google-callback.php',
    ]);
}

function vt_finish_login(string $username, int $uid): void {
    session_regenerate_id(true);
    $_SESSION = ['username' => $username, 'id' => $uid, 'csrf' => bin2hex(random_bytes(32))];
    global $conn; if (isset($conn)) { require_once __DIR__ . '/account_lib.php'; vt_track_session($conn, $uid); }
}

function vt_login_fail(string $key): never {
    $_SESSION['login_err'] = $key;
    header('Location: ' . rtrim(app_url(), '/') . '/login.php'); exit;
}

function vt_http(string $url, ?array $post = null): ?string {
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 10, CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2]);
    if ($post !== null) { curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); }
    $r = curl_exec($ch); $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    return ($r !== false && $code >= 200 && $code < 300) ? $r : null;
}

/** Log in (or create / link) a user from a VERIFIED provider identity. Returns null on success or a lang error key. */
function vt_social_login(mysqli $conn, string $provider, string $puid, string $email, string $name): ?string {
    $email = strtolower(trim($email));
    if ($puid === '' || strlen($puid) > 191) return 'err_social';

    $st = $conn->prepare('SELECT r.id, r.username FROM oauth_accounts o JOIN regjistrimi r ON r.id=o.user_id WHERE o.provider=? AND o.provider_uid=? LIMIT 1');
    $st->bind_param('ss', $provider, $puid); $st->execute();
    if ($row = $st->get_result()->fetch_assoc()) { vt_finish_login($row['username'], (int)$row['id']); return null; }

    // Never let a social identity claim the admin account.
    if ($email === '' || strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $email === 'admin@gmail.com') return 'err_social';

    $st = $conn->prepare('SELECT id, username FROM regjistrimi WHERE email=? LIMIT 1');
    $st->bind_param('s', $email); $st->execute();
    if ($st->get_result()->fetch_assoc()) {
        // An account with this email already exists and Google isn't linked to it: don't auto-link / log in.
        return 'err_social_exists';
    } else {
        $base = trim(preg_replace('/[^\p{L}\p{N}_. -]+/u', '', $name));
        if (mb_strlen($base) < 3) $base = preg_replace('/[^\p{L}\p{N}_.-]+/u', '', (string)strstr($email, '@', true));
        $base = mb_substr($base, 0, 24);
        if (mb_strlen($base) < 3 || strtolower($base) === 'admin') $base = 'traveller';
        $uname = $base;
        $chk = $conn->prepare('SELECT id FROM regjistrimi WHERE username=? LIMIT 1');
        for ($i = 0; $i < 5; $i++) {
            $chk->bind_param('s', $uname); $chk->execute();
            if ($chk->get_result()->num_rows === 0) break;
            $uname = $base . '-' . random_int(1000, 9999);
        }
        $hash = password_hash(bin2hex(random_bytes(32)), PASSWORD_DEFAULT); // unusable random password
        $ins = $conn->prepare('INSERT INTO regjistrimi (username, email, password, has_password) VALUES (?,?,?,0)');
        $ins->bind_param('sss', $uname, $email, $hash);
        if (!$ins->execute()) return 'err_social';
        $uid = (int)$conn->insert_id;
    }
    $lk = $conn->prepare('INSERT IGNORE INTO oauth_accounts (user_id, provider, provider_uid) VALUES (?,?,?)');
    $lk->bind_param('iss', $uid, $provider, $puid); $lk->execute();
    vt_finish_login($uname, $uid);
    return null;
}
