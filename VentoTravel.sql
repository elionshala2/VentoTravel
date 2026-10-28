SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ventotravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE IF NOT EXISTS `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `small_description` text NOT NULL,
  `big_description` text NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `title`, `photo_url`, `small_description`, `big_description`, `price`) VALUES
(1, 'Pogradec', 'img/Pogradec.jpg', 'Pushime buzë Liqenit të Ohrit në Pogradec.', 'Eksploroni bukuritë natyrore të Pogradecit, shijoni ushqimet tradicionale si korani dhe vizitoni parkun e Drilonit.', '120.00'),
(2, 'Dubrovnik', 'img/Dubrovnik.jpg', 'Qyteti antik bregdetar i Kroacisë.', 'Dubrovnik ofron pamje mbresëlënëse të Detit Adriatik, kalatë historike mesjetare dhe rrugicat e njohura prej guri.', '250.00'),
(3, 'Budva', 'img/Budva.jpg', 'Kryeqyteti i turizmit bregdetar në Mal të Zi.', 'Budva njihet për plazhet e saj të mrekullueshme, jetën e gjallë të natës dhe Qytetin e Vjetër historik.', '180.00'),
(4, 'Itali', 'img/Itali.jpg', 'Udhëtim kulturor dhe turistik në Itali.', 'Zbuloni arkitekturën klasike, ushqimin e mrekullueshëm italian dhe qytetet më të njohura historike të Evropës.', '320.00'),
(5, 'Greqi', 'img/Greqi.jpg', 'Pushime në plazhet e kristalta të Greqisë.', 'Shijoni diellin, detin me ujëra të kthjellëta dhe mikpritjen tradicionale greke në Ishujt Jonianë.', '220.00'),
(6, 'Matka', 'img/Matka.jpg', 'Kanjoni mahnitës i Matkës pranë Shkupit.', 'Një destinacion ideal për adhuruesit e natyrës, lundrimin me varkë, eksplorimin e shpellave dhe ecjet në natyrë.', '45.00'),
(7, 'Zadar', 'img/KosoveZadar.jpg', 'Bregdeti historik dhe natyra e Kroacisë.', 'Vizitoni Orgat e Detit në Zadar, shijoni perëndimet e diellit dhe eksploroni monumentet romake e mesjetare.', '210.00'),
(8, 'Zagreb', 'img/KosoveZagreb.jpg', 'Udhëtim në kryeqytetin kulturor të Kroacisë.', 'Zagreb ofron arkitekturë austro-hungareze, muze unikë, parqe të gjelbra dhe kafe tradicionale evropiane.', '190.00'),
(9, 'Gjermani', 'img/KosoveGjermani.jpg', 'Eksploroni qytetet dhe kështjellat e Gjermanisë.', 'Vizitoni qendrat kulturore, kështjellat historike të Bavarisë dhe infrastrukturën moderne gjermane.', '350.00'),
(10, 'Dajt', 'img/dajt.jpg', 'Aventurë me teleferik në Malit e Dajtit.', 'Shijoni pamjen panoramike të Tiranës, ajrin e pastër të malit dhe aktivitetet rekreative në parkun kombëtar.', '35.00'),
(11, 'Struge', 'img/OherStruge.jpg', 'Qyteti i poezisë buzë Drinit të Zi.', 'Një vend i qetë për pushime buzë liqenit, i njohur për shëtitoren buzë lumit dhe mikpritjen e ngrohtë.', '95.00'),
(12, 'Kroaci', 'img/Kroaci.jpg', 'Tur bregdetar në rivierën kroate.', 'Eksploroni ishujt e mrekullueshëm të Adriatikut, parqet kombëtare si Plitvice dhe qytetet bregdetare.', '280.00');

-- --------------------------------------------------------

--
-- Table structure for table `email_codes`
--

CREATE TABLE IF NOT EXISTS `email_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `purpose` varchar(20) NOT NULL,
  `code_hash` char(64) NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_code` (`user_id`,`purpose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(1, 'What are your business hours?', 'Our support team is available Monday through Friday from 9:00 AM to 6:00 PM EST.'),
(2, 'How long does shipping take?', 'Standard domestic shipping typically takes 3 to 5 business days, while international shipping takes 7 to 14 business days.'),
(3, 'Do you ship internationally?', 'Yes, we ship to over 50 countries worldwide. Shipping costs will be calculated at checkout.'),
(4, 'How can I track my order?', 'Once your order ships, you will receive an email notification containing your tracking number and a link to trace the package.'),
(5, 'What is your return policy?', 'You can return any unused item in its original packaging within 30 days of delivery for a full refund.'),
(6, 'How do I initiate a return or exchange?', 'Contact our support team with your order number and reason for return, and we will send you a prepaid shipping label.'),
(7, 'What payment methods do you accept?', 'We accept Visa, Mastercard, American Express, PayPal, Apple Pay, and Google Pay.'),
(8, 'Can I change or cancel my order after placing it?', 'Orders can be modified or canceled within 1 hour of placement. After that, the order enters processing and cannot be changed.'),
(9, 'Do I need an account to place an order?', 'No, you can check out as a guest. However, creating an account lets you view order history and save address details.'),
(10, 'How do I reset my account password?', 'Click on the \"Forgot Password\" link on the login page, enter your registered email, and follow the password reset instructions.'),
(11, 'Are my payment details secure?', 'Yes, all transactions are processed through encrypted, PCI-DSS compliant payment gateways.'),
(12, 'What should I do if I receive a damaged item?', 'Send a clear photo of the damaged item and packaging to support along with your order number within 48 hours of delivery for a replacement.'),
(13, 'Do you offer bulk or wholesale discounts?', 'Yes, we offer tier-based pricing for bulk orders. Please reach out to our sales team for custom quotes.'),
(14, 'How do I update my shipping address?', 'You can update your default shipping address inside your account settings or contact support before your order ships.'),
(15, 'Where is my order confirmation email?', 'Confirmation emails are sent immediately. If you do not see it in your primary inbox, please check your spam or promo folders.'),
(16, 'Do you issue gift cards?', 'Yes, digital gift cards are available for purchase in amounts ranging from $10 to $500 and never expire.'),
(17, 'How do promo codes work?', 'Enter your discount code in the promo box during checkout, and the discount will apply automatically to eligible items.'),
(18, 'What happens if a package is marked delivered but I haven\'t received it?', 'First check with neighbors or local delivery carriers. If it cannot be located after 24 hours, contact customer support.'),
(19, 'How can I contact customer support?', 'You can reach us via live chat on our website or by sending an email to support@example.com.'),
(20, 'Is my personal information kept private?', 'We never sell or rent your personal data to third parties. Read our Privacy Policy for full details on how we protect your information.');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE IF NOT EXISTS `feedbacks` (
  `feedback_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`feedback_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_accounts`
--

CREATE TABLE IF NOT EXISTS `oauth_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `provider` varchar(20) NOT NULL,
  `provider_uid` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_provider_uid` (`provider`,`provider_uid`),
  KEY `idx_oauth_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passkeys`
--

CREATE TABLE IF NOT EXISTS `passkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `credential_id` varchar(700) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `public_key` text NOT NULL,
  `sign_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(80) NOT NULL DEFAULT 'Passkey',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_credential` (`credential_id`),
  KEY `idx_passkey_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_signups`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `regjistrimi`
--

CREATE TABLE IF NOT EXISTS `regjistrimi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `has_password` tinyint(1) NOT NULL DEFAULT 1,
  `twofa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rezervimet`
--

CREATE TABLE IF NOT EXISTS `rezervimet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

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
  KEY `idx_us_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `oauth_accounts`
--
ALTER TABLE `oauth_accounts`
  ADD CONSTRAINT `fk_oauth_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `passkeys`
--
ALTER TABLE `passkeys`
  ADD CONSTRAINT `fk_passkey_user` FOREIGN KEY (`user_id`) REFERENCES `regjistrimi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;