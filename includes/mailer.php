<?php
// Minimal SMTP mailer (no dependencies). .env keys: SMTP_HOST, SMTP_PORT (587/465), SMTP_USER, SMTP_PASS, SMTP_FROM, SMTP_FROM_NAME, SMTP_SECURE (tls|ssl|none)
// If SMTP_HOST is empty it falls back to PHP mail().
function vt_mail(string $to, string $subject, string $body, ?string $html = null): bool {
    $GLOBALS['VT_MAIL_ERR'] = '';
    $host = env('SMTP_HOST');
    $from = env('SMTP_FROM', env('SMTP_USER', env('CONTACT_EMAIL', 'no-reply@localhost')));
    $fromName = env('SMTP_FROM_NAME', 'VentoTravel');
    $bnd = 'vt_' . bin2hex(random_bytes(8));
    $ct = $html === null ? "Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n" : "Content-Type: multipart/alternative; boundary=\"$bnd\"\r\n";
    $hdr = "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <$from>\r\nTo: <$to>\r\nSubject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\nDate: " . date('r') .
        "\r\nMessage-ID: <" . bin2hex(random_bytes(8)) . "@ventotravel>\r\nMIME-Version: 1.0\r\n" . $ct;
    if ($html === null) $data = chunk_split(base64_encode($body));
    else $data = "--$bnd\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($body)) .
        "--$bnd\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . chunk_split(base64_encode($html)) . "--$bnd--\r\n";
    if (!$host) { $ok = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $data, "From: $from\r\nMIME-Version: 1.0\r\n" . $ct); if (!$ok) $GLOBALS['VT_MAIL_ERR'] = 'SMTP_HOST is not set in .env and PHP mail() is not configured'; return $ok; }
    $port = (int)env('SMTP_PORT', 587); $sec = strtolower((string)env('SMTP_SECURE', $port === 465 ? 'ssl' : 'tls'));
    try {
        $verify = !in_array(strtolower((string)env('SMTP_VERIFY_TLS', '1')), ['0', 'false', 'no'], true);
        $ctx = stream_context_create(['ssl' => ['verify_peer' => $verify, 'verify_peer_name' => $verify, 'allow_self_signed' => !$verify]]);
        $fp = @stream_socket_client(($sec === 'ssl' ? 'ssl://' : 'tcp://') . "$host:$port", $en, $es, 15, STREAM_CLIENT_CONNECT, $ctx);
        if (!$fp) throw new Exception("cannot connect to $host:$port ($es)");
        stream_set_timeout($fp, 15);
        $rd = function () use ($fp) { $r = ''; while (($l = fgets($fp, 515)) !== false) { $r .= $l; if (strlen($l) < 4 || $l[3] === ' ') break; } return $r; };
        $cmd = function (string $c, array $ok) use ($fp, $rd) { if ($c !== '') fwrite($fp, $c . "\r\n"); $r = $rd(); if (!in_array(substr($r, 0, 3), $ok, true)) throw new Exception("SMTP: $r"); return $r; };
        $cmd('', ['220']);
        $ehlo = 'EHLO ' . (parse_url(app_url(), PHP_URL_HOST) ?: 'localhost');
        $cmd($ehlo, ['250']);
        if ($sec === 'tls') {
            $cmd('STARTTLS', ['220']);
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) throw new Exception('TLS handshake failed (if on XAMPP/localhost, set SMTP_VERIFY_TLS=0 in .env)');
            $cmd($ehlo, ['250']);
        }
        if (env('SMTP_USER')) {
            $cmd('AUTH LOGIN', ['334']); $cmd(base64_encode(env('SMTP_USER')), ['334']); $cmd(base64_encode((string)env('SMTP_PASS', '')), ['235']);
        }
        $cmd("MAIL FROM:<$from>", ['250']); $cmd("RCPT TO:<$to>", ['250', '251']); $cmd('DATA', ['354']);
        fwrite($fp, $hdr . "\r\n" . $data . "\r\n.\r\n"); $r = $rd(); if (substr($r, 0, 3) !== '250') throw new Exception("SMTP: $r");
        $cmd('QUIT', ['221', '250']); fclose($fp);
        return true;
    } catch (Throwable $e) { $GLOBALS['VT_MAIL_ERR'] = trim(preg_replace('/\s+/', ' ', $e->getMessage())); error_log('Mail error: ' . $GLOBALS['VT_MAIL_ERR']); return false; }
}
