<?php
// Stray PHP notices/deprecations would corrupt the JSON the browser expects -> silence and buffer them.
ini_set('display_errors', '0'); error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ob_start();
use lbuchs\WebAuthn\WebAuthn;
use lbuchs\WebAuthn\Binary\ByteBuffer;
require_once 'includes/security.php';
require_once 'config.php';
require_once 'includes/auth_lib.php';
vt_session_start(); vt_security_headers();
header('Content-Type: application/json; charset=utf-8');

function jout(array $a, int $code = 200): never { while (ob_get_level()) ob_end_clean(); header('Content-Type: application/json; charset=utf-8'); http_response_code($code); echo json_encode($a); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jout(['error' => 'method'], 405);
$hdr = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (!is_string($hdr) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $hdr)) jout(['error' => 'csrf'], 403);
if (!passkeys_enabled()) jout(['error' => 'setup'], 500);

$in = json_decode((string)file_get_contents('php://input'), true);
$in = is_array($in) ? $in : [];
$action = (string)($_GET['a'] ?? '');
$rpId = parse_url(app_url(), PHP_URL_HOST) ?: 'localhost';
$wa = new WebAuthn('VentoTravel', $rpId, ['none']);
$b64 = fn($k) => base64_decode((string)($in[$k] ?? ''), true);
$takeChallenge = function () {
    $c = $_SESSION['wa_chal'] ?? ''; unset($_SESSION['wa_chal']);
    return $c === '' ? null : new ByteBuffer(base64_decode($c));
};

try {
    switch ($action) {
        case 'login_options':
            if (rl_count('pk|' . client_ip(), 900) >= 30) jout(['error' => 'rate'], 429);
            $args = $wa->getGetArgs([], 60, requireUserVerification: true);   // empty list = discoverable passkeys
            $_SESSION['wa_chal'] = base64_encode($wa->getChallenge()->getBinaryString());
            jout((array)json_decode(json_encode($args), true));

        case 'login_verify':
            $chal = $takeChallenge();
            $cid = $in['id'] ?? ''; $cdj = $b64('clientDataJSON'); $ad = $b64('authenticatorData'); $sig = $b64('signature');
            if (!$chal || !is_string($cid) || $cdj === false || $ad === false || $sig === false || rl_count('pk|' . client_ip(), 900) >= 30) { rl_hit('pk|' . client_ip(), 900); jout(['error' => 'invalid'], 400); }
            $st = $conn->prepare('SELECT p.id, p.user_id, p.public_key, p.sign_count, r.username FROM passkeys p JOIN regjistrimi r ON r.id=p.user_id WHERE p.credential_id=? LIMIT 1');
            $st->bind_param('s', $cid); $st->execute();
            $row = $st->get_result()->fetch_assoc();
            if (!$row) { rl_hit('pk|' . client_ip(), 900); jout(['error' => 'invalid'], 400); }
            try {
                $wa->processGet($cdj, $ad, $sig, $row['public_key'], $chal, (int)$row['sign_count'], true, true);
            } catch (Throwable $e) { rl_hit('pk|' . client_ip(), 900); jout(['error' => 'invalid'], 400); }
            $cnt = (int)($wa->getSignatureCounter() ?? 0);
            $up = $conn->prepare('UPDATE passkeys SET sign_count=?, last_used_at=NOW() WHERE id=?');
            $up->bind_param('ii', $cnt, $row['id']); $up->execute();
            vt_finish_login($row['username'], (int)$row['user_id']);
            jout(['ok' => true, 'redirect' => 'index.php']);

        case 'reg_options':
        case 'reg_verify':
            $uid = (int)($_SESSION['id'] ?? 0);
            if ($uid <= 0) jout(['error' => 'auth'], 401);
            if ($action === 'reg_options') {
                $st = $conn->prepare('SELECT credential_id FROM passkeys WHERE user_id=?');
                $st->bind_param('i', $uid); $st->execute();
                $ids = []; $n = 0;
                foreach ($st->get_result() as $r) { $ids[] = base64_decode($r['credential_id']); $n++; }
                if ($n >= 10) jout(['error' => 'limit'], 400);
                $_SESSION['wa_uid'] = $uid;
                $args = $wa->getCreateArgs(hash('sha256', 'vt|' . $uid, true), (string)$_SESSION['username'], (string)$_SESSION['username'], 60,
                    requireResidentKey: true, requireUserVerification: true, excludeCredentialIds: $ids);
                $_SESSION['wa_chal'] = base64_encode($wa->getChallenge()->getBinaryString());
                jout((array)json_decode(json_encode($args), true));
            }
            $chal = $takeChallenge(); $cdj = $b64('clientDataJSON'); $ao = $b64('attestationObject');
            if (!$chal || $cdj === false || $ao === false) jout(['error' => 'invalid'], 400);
            if (($_SESSION['wa_uid'] ?? 0) !== $uid) jout(['error' => 'invalid'], 400);
            $d = $wa->processCreate($cdj, $ao, $chal, true, true, false, false);
            $cid = base64_encode($d->credentialId);
            $name = mb_substr(trim(strip_tags((string)($in['name'] ?? ''))), 0, 60) ?: 'Passkey';
            $ins = $conn->prepare('INSERT INTO passkeys (user_id, credential_id, public_key, sign_count, name) VALUES (?,?,?,?,?)');
            $sc = (int)($d->signatureCounter ?? 0);
            $ins->bind_param('issis', $uid, $cid, $d->credentialPublicKey, $sc, $name); $ins->execute();
            unset($_SESSION['wa_uid']);
            jout(['ok' => true]);

        default: jout(['error' => 'action'], 400);
    }
} catch (Throwable $e) {
    error_log('Passkey error: ' . $e->getMessage());
    jout(['error' => 'failed'], 400);
}
