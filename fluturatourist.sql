-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2023 at 04:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fluturatourist`
--

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `small_description` text NOT NULL,
  `big_description` text NOT NULL,
  `price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `title`, `photo_url`, `small_description`, `big_description`, `price`) VALUES
(12, 'Kosove - Gjermani', 'img/KosoveGjermani.jpg', 'Ju mirëpresim për rezervime!', 'Tani do të keni mundësi të udhëtoni në drejtim të Gjermanis , si dhe anasjelltas, qdo javë.', '40'),
(14, 'Matka', 'img/Matka.jpg', '🌿Vikendi në : Matka, City Mall, Kopshti Zoologjik dhe Shkup🌿', '🔴Agjenda e ditës: 🟡Vizitohet Kanioni i Matkës 🔴Vizitohet qendra tregtare City Mall 🟡Vizitohet Kopshti Zoologjik (Pagesa individuale) 🔴Vizitohet qyteti i Shkupit 📍 Nisja nga Podujeva: 06:00 ( Agjensioni FLUTURA REISEN) 📍 Nisja nga Prishtina: 07:00 ( Agjensioni  FLUTURA REISEN ) 📍Nisja nga Fushë Kosova 07:05 ( te rrethi) 📍Nisja nga Ferizaj te Hib Petrol Bibaj', '8'),
(15, 'Kosove - Zadar', 'img/KosoveZadar.jpg', 'Ju mirëpresim për rezervime!', 'Tani do të keni mundësi të udhëtoni në drejtim të Gjermanis , si dhe anasjelltas, qdo javë.', '30'),
(16, 'Dubrovnik', 'img/Dubrovnik.jpg', '📍 Fundjavë në Dubrovnik - 16 Shkurt 2024', '𝐏𝐞̈𝐫𝐟𝐢𝐭𝐨 𝟓% 𝐳𝐛𝐫𝐢𝐭𝐣𝐞 𝐩𝐞̈𝐫 𝐭𝐞̈ 𝐠𝐣𝐢𝐭𝐡𝐚 𝐫𝐞𝐳𝐞𝐫𝐯𝐢𝐦𝐞𝐭 𝐭𝐞̈ 𝐜𝐢𝐥𝐚𝐭 𝐛𝐞̈𝐡𝐞𝐧 𝐝𝐞𝐫𝐢 𝐦𝐞̈ 𝐝𝐚𝐭𝐞̈𝐧 𝟐𝟖 𝐍𝐞̈𝐧𝐭𝐨𝐫!  Të përfshira në çmim: ✅ Bileta e autobusit  ✅ Bagazh 20kg  ✅ Akomodimi në hotel ✅ Shërbimi në hotel me mengjes ✅ Taksat e qytetit  ✅ Shoqerues shqipëtarë ', '199'),
(17, 'Itali', 'img/Itali.jpg', '📍 Rreth e rrotull Italisë -  21 Mars 2024', '𝐏𝐞̈𝐫𝐟𝐢𝐭𝐨 𝟓% 𝐳𝐛𝐫𝐢𝐭𝐣𝐞 𝐩𝐞̈𝐫 𝐭𝐞̈ 𝐠𝐣𝐢𝐭𝐡𝐚 𝐫𝐞𝐳𝐞𝐫𝐯𝐢𝐦𝐞𝐭 𝐭𝐞̈ 𝐜𝐢𝐥𝐚𝐭 𝐛𝐞̈𝐡𝐞𝐧 𝐝𝐞𝐫𝐢 𝐦𝐞̈ 𝐝𝐚𝐭𝐞̈𝐧 𝟐𝟖 𝐍𝐞̈𝐧𝐭𝐨𝐫!  Të përfshira në çmim: ✅ Bileta e autobusit  ✅ Bagazh 20kg  ✅ Akomodimi në hotel ✅ Shërbimi në hotel me mengjes ✅ Taksat e qytetit  ✅ Shoqerues shqipëtarë ', '870'),
(18, 'Kroaci', 'img/Kroaci.jpg', '📍 Aventurë në Kroaci - 18 Prill 2024', 'Të përfshira në çmim: ✅ Bileta e autobusit  ✅ Bagazh 20kg  ✅ Akomodimi në hotel ✅ Shërbimi në hotel me mengjes ✅ Taksat e qytetit  ✅ Shoqerues shqipëtarë ', '485'),
(19, 'Greqi', 'img/Greqi.jpg', '📍 Aventurë në Ishujt e Greqise - 8 Maj 2024', 'Të përfshira në çmim: ✅ Bileta e autobusit  ✅ Bagazh 20kg  ✅ Akomodimi në hotel ✅ Shërbimi në hotel me mengjes ✅ Taksat e qytetit  ✅ Shoqerues shqipëtarë ', '1285'),
(20, 'Oher & Struge', 'img/OherStruge.jpg', '🌺Vikendi në #Strugë dhe #Ohër🌺', '📍 Nisja nga Podujeva: 06:00 ( Agjensioni FLUTURA REISEN) 📍 Nisja nga Prishtina: 07:00 ( Agjensioni  FLUTURA REISEN ) 📍Nisja nga Fushë Kosova 07:05 ( te rrethi) 📍 Nisja nga Ferizaj: 07:40 ( HIB Petrol - Bibaj )', '15'),
(26, 'Budva', 'img/Budva.jpg', '💥Festa e 28 Nëntorit 💥', '⚫️Ne çmim 50 €uro perfshihet akomodimi ne Hotel në Ulqin  ,fjetje,mengjes dhe Darkë transporti me autobus super komod. ⚫️Vizitat: Shkodres,Budva,Ulqin,Kotor,Sv stefan, Tivat   gjatë udhëtimit  pijet, ëmbëlsirat dhe shërbimet gratis në autobus. ', '30'),
(27, 'Kosove - Zagreb', 'img/KosoveZagreb.jpg', 'Zagrebi eshte nje vend i mire.', 'Zagrebi gjendet ne Kroaci, dhe eshte nje vend shume i mire!', '40'),
(28, 'Dajt - Mal', 'img/dajt.jpg', 'Dajt eshte nje mal', 'Ky eshte nje pershkrim i gjate', '30');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(5, 'Cilat shërbime ofron agjencia juaj turistike?', 'Ne ofrojmë një gamë të gjerë shërbimesh udhëtimi, duke përfshirë rezervimet e fluturimeve, rezervimet e hoteleve, paketat e pushimeve, udhëtimet e udhëzuar dhe shumë të tjera.'),
(6, 'Si mund të bëj rezervimin e një udhëtimi me agjencinë tuaj?', 'Mund të bëni lehtësisht rezervimin tuaj përmes faqes sonë zyrtare, të vizitoni zyrën tonë, ose të kontaktoni shërbimin tonë të klientëve. Ekipi ynë do t\'ju asistojë në planifikimin dhe rezervimin e udhëtimit tuaj të dëshiruar.'),
(7, 'Cilat janë destinacionet më të preferuara?', 'Destinacionet më të preferuara përfshijnë plazhet e bukura, qytetet historike dhe destinacionet natyrore.'),
(8, 'Ka oferta speciale për paketa turistike?', 'Po, ofrojmë oferta speciale dhe paketa turistike me çmime të reduktuara në periudha të caktuara.'),
(9, 'Sa kohë paraprakisht duhet të rezervoj?', 'Këshillojmë të rezervoni sa më parë që të jenë të disponueshme opsionet e preferuara.'),
(10, 'Mund të ndryshoj datën e udhëtimit pas rezervimit?', 'Mund të ketë kufizime, por mund të kontaktoni për asistence në rast ndryshimesh.'),
(11, 'Cilat janë opsionet e pagesës?', 'Opsionet e pagesës përfshijnë pagesën me kartë krediti dhe transferim bankar.'),
(12, 'Ofroni shërbime sigurie gjatë udhëtimit?', 'Po, ofrojmë shërbime sigurie dhe asistencë në çdo hap të udhëtimit tuaj.'),
(13, 'Si marr informacion për vizat dhe dokumentet e nevojshme?', 'Mund të gjeni informacionin për vizat dhe dokumentet e nevojshme në faqen tonë të internetit ose të na kontaktoni.'),
(14, 'A ka shërbim për klientët në rast emergjence?', 'Po, ofrojmë shërbim për klientët në rast emergjence. Numri kontaktues është në dispozicion në dokumentacionin e udhëtimit.'),
(15, 'Mund të rezervoj një udhëtim për grup?', 'Po, mund të rezervoni një udhëtim për grup dhe mund të përfitoni nga oferta speciale për grupet.'),
(16, 'Si mund të aplikoj për pako pushimesh?', 'Aplikimi për pako pushimesh bëhet nëpërmjet faqes sonë të internetit ose duke na kontaktuar për asistencë.'),
(28, 'Pyetja 2', 'Pergjigja 2');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regjistrimi`
--

CREATE TABLE `regjistrimi` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regjistrimi`
--

INSERT INTO `regjistrimi` (`id`, `username`, `password`, `email`) VALUES
(1, 'Elion', '202cb962ac59075b964b07152d234b70', 'elion@gmail.com'),
(2, 'Elion123', '81dc9bdb52d04dc20036dbd8313ed055', 'elionshala@gmail.com'),
(3, 'elionshala2', '202cb962ac59075b964b07152d234b70', 'elionshalaa@gmail.com'),
(4, 'elionshala20', '202cb962ac59075b964b07152d234b70', 'elionnshala@gmail.com'),
(5, 'ElionShala', '202cb962ac59075b964b07152d234b70', 'shalaelion1@gmail.com'),
(6, 'ElionShala123123', '202cb962ac59075b964b07152d234b70', 'Elionshala123123@gmail.com'),
(7, 'Elionshalaaaa', '202cb962ac59075b964b07152d234b70', 'elionshala123123123@gmail.com'),
(8, 'Elion Shala 123', '202cb962ac59075b964b07152d234b70', 'elionshala123123321@gmail.com'),
(9, 'elionshala2020', '202cb962ac59075b964b07152d234b70', 'elionshala2020@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `rezervimet`
--

CREATE TABLE `rezervimet` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `regjistrimi`
--
ALTER TABLE `regjistrimi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rezervimet`
--
ALTER TABLE `rezervimet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `regjistrimi`
--
ALTER TABLE `regjistrimi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rezervimet`
--
ALTER TABLE `rezervimet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
