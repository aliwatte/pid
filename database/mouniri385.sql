-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 01, 2020 at 01:50 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `reservations`
--
DROP DATABASE IF EXISTS `mouniri385`;
CREATE DATABASE IF NOT EXISTS `mouniri385` DEFAULT CHARACTER SET utf8;
USE `mouniri385`;

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `artists`;
CREATE TABLE IF NOT EXISTS `artists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `firstname`, `lastname`) VALUES
(1, 'Bob', 'Sull'),
(2, 'Marc', 'Flynn'),
(3, 'Fred', 'Durand');

-- --------------------------------------------------------

--
-- Table structure for table `artist_type`
--
-- Creation: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `artist_type`;
CREATE TABLE IF NOT EXISTS `artist_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3060D1B6B7970CF8` (`artist_id`),
  KEY `IDX_3060D1B6C54C8C93` (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artist_type`
--

INSERT INTO `artist_type` (`id`, `artist_id`, `type_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 2),
(4, 3, 3),
(5, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `collaborations`
--
-- Creation: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `collaborations`;
CREATE TABLE IF NOT EXISTS `collaborations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist_type_id` int(11) NOT NULL,
  `shows` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F90F069B7203D2A4` (`artist_type_id`),
  KEY `IDX_F90F069B6C3BF144` (`shows`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `collaborations`
--

INSERT INTO `collaborations` (`id`, `artist_type_id`, `shows`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 2),
(6, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `localities`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `localities`;
CREATE TABLE IF NOT EXISTS `localities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postal_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locality` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `localities`
--

INSERT INTO `localities` (`id`, `postal_code`, `locality`) VALUES
(1, '1000', 'Bruxelles'),
(2, '1020', 'Laeken'),
(3, '1030', 'Schaerbeek'),
(4, '1050', 'Ixelles'),
(5, '1070', 'Anderlecht'),
(6, '1180', 'Uccle'),
(7, '1000', 'Bruxelles');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:49 PM
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locality_id` int(11) DEFAULT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_17E64ABA989D9B62` (`slug`),
  KEY `IDX_17E64ABA88823A92` (`locality_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `locality_id`, `slug`, `designation`, `address`, `website`, `phone`) VALUES
(1, 1, 'belfius-art-collection', 'Belfius Art Collection', '50 rue de l\'Écuyer', NULL, NULL),
(2, 1, 'la-samaritaine', 'La Samaritaine', 'rue de la samaritaine', 'www.lasamaritaine.be', '02/511.33.95'),
(3, 1, 'theatre-royal-parc', 'Théâtre Royal du Parc', 'Rue de la Loi 3', 'www.theatreduparc.be', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `migration_versions`;
CREATE TABLE IF NOT EXISTS `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200531223834', '2020-05-31 22:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `representations`
--
-- Creation: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `representations`;
CREATE TABLE IF NOT EXISTS `representations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `show_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `schedule` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C90A401D0C1FC64` (`show_id`),
  KEY `IDX_C90A40164D218E` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `representations`
--

INSERT INTO `representations` (`id`, `show_id`, `location_id`, `schedule`) VALUES
(1, 1, 1, '2020-10-12 13:30:00'),
(2, 1, 3, '2020-10-12 20:30:00'),
(3, 2, NULL, '2020-10-08 13:30:00'),
(4, 2, NULL, '2020-10-14 20:30:00'),
(5, 3, NULL, '2020-10-08 20:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--
-- Creation: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `representation_id` int(11) NOT NULL,
  `places` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4DA239A76ED395` (`user_id`),
  KEY `IDX_4DA23946CE82F4` (`representation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `representation_id`, `places`) VALUES
(1, 3, 1, 2),
(2, 2, 4, 1),
(3, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'membre');

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:48 PM
--

DROP TABLE IF EXISTS `shows`;
CREATE TABLE IF NOT EXISTS `shows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) DEFAULT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bookable` tinyint(1) NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6C3BF144989D9B62` (`slug`),
  KEY `IDX_6C3BF14464D218E` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shows`
--

INSERT INTO `shows` (`id`, `location_id`, `slug`, `title`, `poster_url`, `bookable`, `price`) VALUES
(1, 1, 'ayiti', 'Ayiti', 'images/ayiti.jpg', 1, 9.5),
(2, 2, 'cible-mouvante', 'Cible Mouvante', 'images/cible.jpg', 1, 8),
(3, 1, 'ceci-n-est-pas-chanteur-belge', 'Ceci n\'est pas un chanteur belge', 'images/claudebelgesaison220.jpg', 0, 7.5);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--
-- Creation: Jun 01, 2020 at 01:46 PM
-- Last update: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `type`) VALUES
(1, 'scènographe'),
(2, 'comédien'),
(3, 'décorateur');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jun 01, 2020 at 01:46 PM
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `langue` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9AA08CB10` (`login`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  KEY `IDX_1483A5E9D60322AC` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `login`, `password`, `firstname`, `lastname`, `email`, `langue`) VALUES
(1, 1, 'admin', '$2y$10$X2oVK39OSCrdd9OiPGSvueEufIL/BtndCEN0VNgoR8nFW3GmyO0ym', 'admin', 'admin', 'admin@ad.com', 'fr'),
(2, 2, 'bob', '$2y$10$KEuhaHJuJ71bR.o.C58PGO.mOByyep8waydwhg1gD8rq/k2sJBjpO', 'bob', 'bob', 'bob@bob.com', 'fr'),
(3, 2, 'ali', '$2y$10$2NU2SzslAPkODGe8VDLluu1uqcQnDgm/wnSyFHvSZSgBjGra9Xpv2', 'ali', 'ali', 'ali@ali.com', 'en'),
(4, 2, 'fred', '$2y$10$hEP1atAq36T4kMAbojDzHePg4yy2/kBavlKsoen5zIzcZj83aFdoy', 'fred', 'fred', 'fred@fred.com', 'en');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artist_type`
--
ALTER TABLE `artist_type`
  ADD CONSTRAINT `FK_3060D1B6B7970CF8` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`),
  ADD CONSTRAINT `FK_3060D1B6C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `collaborations`
--
ALTER TABLE `collaborations`
  ADD CONSTRAINT `FK_F90F069B6C3BF144` FOREIGN KEY (`shows`) REFERENCES `shows` (`id`),
  ADD CONSTRAINT `FK_F90F069B7203D2A4` FOREIGN KEY (`artist_type_id`) REFERENCES `artist_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `FK_17E64ABA88823A92` FOREIGN KEY (`locality_id`) REFERENCES `localities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `representations`
--
ALTER TABLE `representations`
  ADD CONSTRAINT `FK_C90A40164D218E` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `FK_C90A401D0C1FC64` FOREIGN KEY (`show_id`) REFERENCES `shows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `FK_4DA23946CE82F4` FOREIGN KEY (`representation_id`) REFERENCES `representations` (`id`),
  ADD CONSTRAINT `FK_4DA239A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shows`
--
ALTER TABLE `shows`
  ADD CONSTRAINT `FK_6C3BF14464D218E` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
