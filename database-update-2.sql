-- Run once (phpMyAdmin > SQL) AFTER database-update.sql.
ALTER TABLE `regjistrimi`
  ADD COLUMN `has_password` tinyint(1) NOT NULL DEFAULT 1,
  ADD COLUMN `twofa_enabled` tinyint(1) NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `sid_hash` char(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `ip` varchar(45) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sid` (`sid_hash`),
  KEY `idx_us_user` (`user_id`),
  CONSTRAINT `fk_us_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `email_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `purpose` varchar(20) NOT NULL,
  `code_hash` char(64) NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code` (`user_id`,`purpose`),
  CONSTRAINT `fk_ec_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Optional: accounts that were originally created through Google (never set a password) can be marked like this:
-- UPDATE regjistrimi r SET has_password=0 WHERE EXISTS (SELECT 1 FROM oauth_accounts o WHERE o.user_id=r.id) AND NOT EXISTS (SELECT 1 FROM user_sessions);

CREATE TABLE IF NOT EXISTS `pending_signups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `code_hash` char(64) NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_pending_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
