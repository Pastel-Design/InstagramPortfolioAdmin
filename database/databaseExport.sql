-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Čtv 14. led 2021, 15:03
-- Verze serveru: 8.0.21
-- Verze PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `database`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `album`
--

DROP TABLE IF EXISTS `album`;
CREATE TABLE IF NOT EXISTS `album` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(512) NOT NULL,
  `dash_title` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `no_photos` int UNSIGNED NOT NULL,
  `added` datetime NOT NULL,
  `edited` datetime NOT NULL,
  `order` int UNSIGNED NOT NULL,
  `visible` int UNSIGNED NOT NULL DEFAULT '1',
  `cover_photo` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `dash_title_UNIQUE` (`dash_title`),
  KEY `fk_album_image1_idx` (`cover_photo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `album`
--

INSERT INTO `album` (`id`, `title`, `dash_title`, `description`, `keywords`, `no_photos`, `added`, `edited`, `order`, `visible`, `cover_photo`) VALUES
(6, 'Sumbissive 23', 'sumbissive-23', 'Sumbissive 23 album', 'submissive,bondage,bdsm', 65, '2021-01-13 16:42:15', '2021-01-13 16:42:15', 0, 1, 205);

-- --------------------------------------------------------

--
-- Struktura tabulky `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(1024) NOT NULL,
  `data_type` text NOT NULL,
  `added` datetime NOT NULL,
  `edited` datetime NOT NULL,
  `title` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `order` int UNSIGNED NOT NULL,
  `album_id` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename_UNIQUE` (`filename`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  KEY `fk_image_album_idx` (`album_id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `image`
--

INSERT INTO `image` (`id`, `filename`, `data_type`, `added`, `edited`, `title`, `description`, `order`, `album_id`) VALUES
(205, '43b1687a878eeb71460bfa30fb015160276e8d6f5ad23a5547cbb87791e0f9d1.jpg', 'jpg', '2021-01-14 12:16:31', '2021-01-14 12:52:22', NULL, NULL, 2, 6),
(206, '258b4c6e4714495d1d0b097a7089efedc2687485533ff9b0d12d05a62f27b651.jpg', 'jpg', '2021-01-14 12:16:31', '2021-01-14 12:52:23', NULL, NULL, 3, 6),
(207, '11d0555c095e37648795cfb4826352b033d3e2e44826a2a5d8b22f32cccf57cd.jpg', 'jpg', '2021-01-14 12:16:31', '2021-01-14 12:52:24', NULL, NULL, 4, 6),
(208, '332f43c822dccf9b5e2f6961e83d3cca9974bd39553c3a45ed0c6a250c8bcca0.jpg', 'jpg', '2021-01-14 12:16:31', '2021-01-14 12:16:31', NULL, NULL, 5, 6),
(209, '8620558a59e0780cfe8a803feaf7f578d9a27ead118277ac5ee4b9f9a6ca929a.jpg', 'jpg', '2021-01-14 12:16:31', '2021-01-14 12:16:31', NULL, NULL, 1, 6),
(210, '875e3ecee90b333a8fe82096e7ec3e9a122f6e572c4726dacf7a7af1e556cff0.jpg', 'jpg', '2021-01-14 12:16:38', '2021-01-14 12:16:38', NULL, NULL, 6, 6),
(211, '970ec04de7666b423d391dfb56c6fc784d624634f06c67f8b33ae14803dc5937.jpg', 'jpg', '2021-01-14 12:16:38', '2021-01-14 12:16:38', NULL, NULL, 7, 6),
(212, '23dbc7da5f0e7fbd2dde25868f299290acec0f01eebb24f49a8118ad5aeb91b4.jpg', 'jpg', '2021-01-14 12:16:38', '2021-01-14 12:16:38', NULL, NULL, 8, 6),
(213, '72e305af2ebf720b7b38b0e54a8d61962bb9046c187f99f2d7dd1f89bf1237d4.jpg', 'jpg', '2021-01-14 12:16:38', '2021-01-14 12:16:38', NULL, NULL, 9, 6),
(214, '61cde34c9248a1f6dab7b76585a8c83d6502f074a01130e17d97dbf8f18314bf.jpg', 'jpg', '2021-01-14 12:16:38', '2021-01-14 12:16:38', NULL, NULL, 10, 6),
(215, '6c4962661e3045d5caaa8acb6c8f7882281189d135a87df28fda63bf5495690e.jpg', 'jpg', '2021-01-14 12:16:44', '2021-01-14 12:16:44', NULL, NULL, 11, 6),
(216, '486b80fbea93db8af4a2293a00039a9c4793d316b1b4acd8175c82673a31d228.jpg', 'jpg', '2021-01-14 12:16:44', '2021-01-14 12:16:44', NULL, NULL, 12, 6),
(217, '395e125e01a55d8ea04f7de489d68584cba15d56d830f991e482c6ba774fe77b.jpg', 'jpg', '2021-01-14 12:16:44', '2021-01-14 12:16:44', NULL, NULL, 13, 6),
(218, 'b4a9384d5d7e27ae801e828de6404f11176edfde9200971d3b2e464967192238.jpg', 'jpg', '2021-01-14 12:16:44', '2021-01-14 12:16:44', NULL, NULL, 14, 6),
(219, '53734c549cc3e9a615f16821725e418079bb319634697746a8ae942d6f3272e8.jpg', 'jpg', '2021-01-14 12:16:44', '2021-01-14 12:16:44', NULL, NULL, 15, 6),
(220, '99e7eb1d870fd04ee9dd3267f5e0b85288d73f49d17feb5e2155ea2663010a03.jpg', 'jpg', '2021-01-14 12:37:12', '2021-01-14 12:37:12', NULL, NULL, 4, NULL),
(225, 'e1db53240293b609c6b19568179512f59bceda4b647fff5669d4bc171882a454.jpg', 'jpg', '2021-01-14 12:49:15', '2021-01-14 12:49:15', NULL, NULL, 2, NULL),
(226, '1d13487c90e2abc105c1e24570c8da7a0b703279144cb92ba8f2a1b15465aa59.jpg', 'jpg', '2021-01-14 12:49:18', '2021-01-14 12:52:13', NULL, NULL, 1, NULL),
(228, '8eef8a988e0a8003038cad43643b21c6985e2661be41402bd6ca1abd0e0894b4.jpg', 'jpg', '2021-01-14 12:49:24', '2021-01-14 12:49:24', NULL, NULL, 3, NULL),
(231, '0a231271fc21408349da695b1c198f5dd00cc3c00cb15cf4f061da3d0bddaa37.jpg', 'jpg', '2021-01-14 14:55:23', '2021-01-14 14:55:23', NULL, NULL, 5, NULL),
(232, '414c12a71bb91b263436b3aa50c4ccb0563519217d875a8891518fc85e429873.jpg', 'jpg', '2021-01-14 14:55:34', '2021-01-14 14:55:34', NULL, NULL, 6, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `landing_page_image`
--

DROP TABLE IF EXISTS `landing_page_image`;
CREATE TABLE IF NOT EXISTS `landing_page_image` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `landing_page_image`
--

INSERT INTO `landing_page_image` (`id`, `filename`) VALUES
(1, 'ebe3ebe0d8c2dcd06ba6376dac05f941f1bb7582e6bb1ae2014b226ca1478aae.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `profile_image`
--

DROP TABLE IF EXISTS `profile_image`;
CREATE TABLE IF NOT EXISTS `profile_image` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabulky `web_info`
--

DROP TABLE IF EXISTS `web_info`;
CREATE TABLE IF NOT EXISTS `web_info` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `default_title` text NOT NULL,
  `default_description` text NOT NULL,
  `default_keywords` text NOT NULL,
  `email` text NOT NULL,
  `login` text NOT NULL,
  `bio_title` text NOT NULL,
  `bio_description` text NOT NULL,
  `instagram_link` varchar(90) NOT NULL,
  `twitter_link` varchar(90) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Vypisuji data pro tabulku `web_info`
--

INSERT INTO `web_info` (`id`, `default_title`, `default_description`, `default_keywords`, `email`, `login`, `bio_title`, `bio_description`, `instagram_link`, `twitter_link`) VALUES
(1, 'Gareth Jorden Portfolio', 'Portfolio of Gareth Jorden Photography', 'bdsm,bondage,photography', 'garethjorden@hotmail.com', '$2y$10$wB5CKa.F1JxX.ernClHYze9zv3JhcTj0V.RkA1lJ1YN2yQG/UmhaG', 'Gareth Jorden', 'Gareth Jorden\nPhotographer , London UK', 'https://www.instagram.com/garethjorden/', 'https://twitter.com/garethjorden');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `fk_album_image1` FOREIGN KEY (`cover_photo`) REFERENCES `image` (`id`);

--
-- Omezení pro tabulku `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_album` FOREIGN KEY (`album_id`) REFERENCES `album` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
