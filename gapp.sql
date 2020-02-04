-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Erstellungszeit: 30. Nov 2019 um 16:32
-- Server-Version: 5.7.26
-- PHP-Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `gapp`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `uprv`
--

DROP TABLE IF EXISTS `uprv`;
CREATE TABLE IF NOT EXISTS `uprv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Ucode` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Pname` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Pvalue` varchar(1) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'N',
  `CreateDate` datetime DEFAULT NULL,
  `CreateUcode` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateUcode` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Ucode` (`Ucode`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Daten für Tabelle `uprv`
--

INSERT INTO `uprv` (`id`, `Ucode`, `Pname`, `Pvalue`, `CreateDate`, `CreateUcode`, `UpdateDate`, `UpdateUcode`) VALUES
(1, 'MANAGER', 'users_modify', 'Y', '2014-04-09 19:50:00', 'MANAGER', '2019-11-09 14:14:30', 'GAB'),
(2, 'MANAGER', 'users_read', 'Y', '2016-02-15 11:31:06', 'GAB', '2019-11-09 14:14:30', 'GAB'),
(3, 'MANAGER', 'lang_read', 'Y', '2016-02-15 11:31:57', 'GAB', '2019-11-10 08:55:16', 'MANAGER');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Ucode` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Uname` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Email` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Telefon` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Superu` varchar(1) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Locked` varchar(1) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `wrongLogin` int(11) DEFAULT NULL,
  `Pwd` varchar(254) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `UpdatePwd` datetime DEFAULT NULL,
  `IsDefPW` varchar(10) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `Fn` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `CreateUcode` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateUcode` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Ucode` (`Ucode`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `Ucode`, `Uname`, `Email`, `Telefon`, `Superu`, `Locked`, `wrongLogin`, `Pwd`, `UpdatePwd`, `IsDefPW`, `Fn`, `CreateDate`, `CreateUcode`, `UpdateDate`, `UpdateUcode`) VALUES
(1, 'MANAGER', 'Gábor Brunner', 'gab@gab.hus', NULL, 'Y', 'N', 0, '$2y$12$gXmIukgzEmS6MR2aFPBNzeRnLXXe58U4aPQ2qZGVTRasOmJD0Qucm', '2019-11-30 16:18:34', 'N', 'The FN', '2013-05-04 06:16:16', 'MANAGER', '2019-11-30 16:23:42', 'MANAGER');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
