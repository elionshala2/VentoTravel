-- Run this once on the VentoTravel database (phpMyAdmin > SQL).
-- Adds support for Google/Apple sign-in and passkeys. Existing data is untouched.

CREATE TABLE IF NOT EXISTS `oauth_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider` varchar(20) NOT NULL,
  `provider_uid` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_provider_uid` (`provider`,`provider_uid`),
  KEY `idx_oauth_user` (`user_id`),
  CONSTRAINT `fk_oauth_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `passkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credential_id` varchar(700) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `public_key` text NOT NULL,
  `sign_count` int(10) unsigned NOT NULL DEFAULT 0,
  `name` varchar(80) NOT NULL DEFAULT 'Passkey',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_credential` (`credential_id`),
  KEY `idx_passkey_user` (`user_id`),
  CONSTRAINT `fk_passkey_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
