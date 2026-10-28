<?php
// Idempotent auto-migration: creates the columns/tables the account features need if they are missing.
function vt_ensure_schema(mysqli $conn): void {
    $flag = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'vento_schema_v3_' . md5(__DIR__ . (string)env('DB_NAME', ''));
    if (is_file($flag)) return;
    try {
        foreach (['has_password' => 'tinyint(1) NOT NULL DEFAULT 1', 'twofa_enabled' => 'tinyint(1) NOT NULL DEFAULT 0'] as $col => $def) {
            if (!$conn->query("SHOW COLUMNS FROM `regjistrimi` LIKE '$col'")->num_rows) $conn->query("ALTER TABLE `regjistrimi` ADD COLUMN `$col` $def");
        }
        $conn->query("CREATE TABLE IF NOT EXISTS `user_sessions` (
          `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL,
          `sid_hash` char(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
          `ip` varchar(45) NOT NULL DEFAULT '', `user_agent` varchar(255) NOT NULL DEFAULT '',
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(), `last_seen` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`), UNIQUE KEY `uq_sid` (`sid_hash`), KEY `idx_us_user` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        $conn->query("CREATE TABLE IF NOT EXISTS `email_codes` (
          `id` int(11) NOT NULL AUTO_INCREMENT, `user_id` int(11) NOT NULL, `purpose` varchar(20) NOT NULL,
          `code_hash` char(64) NOT NULL, `attempts` tinyint(4) NOT NULL DEFAULT 0, `expires_at` datetime NOT NULL,
          PRIMARY KEY (`id`), UNIQUE KEY `uq_code` (`user_id`,`purpose`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        $conn->query("CREATE TABLE IF NOT EXISTS `pending_signups` (
          `id` int(11) NOT NULL AUTO_INCREMENT, `email` varchar(255) NOT NULL, `username` varchar(255) NOT NULL,
          `password_hash` varchar(255) NOT NULL, `code_hash` char(64) NOT NULL, `attempts` tinyint(4) NOT NULL DEFAULT 0,
          `expires_at` datetime NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `uq_pending_email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        @file_put_contents($flag, '1');
    } catch (Throwable $e) { error_log('Schema: ' . $e->getMessage()); }
}
