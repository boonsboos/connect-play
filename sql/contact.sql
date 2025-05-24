-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Gegenereerd op: 22 mei 2025 om 09:30
-- Serverversie: 11.7.2-MariaDB-ubu2404
-- PHP-versie: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--
USE `webshop`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `message` TEXT(3000) NOT NULL,
  `created_at` TIMESTAMP NOT NULL
);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Procedures
DELIMITER //
-- contact toevoegen aan de database
CREATE PROCEDURE add_contact(
    IN p_first_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_message TEXT(3000)
)
BEGIN
	INSERT INTO contact (
        `first_name`,
        `last_name`,
        `email`,
        `message`,
        `created_at`
	)
    VALUES (
        p_first_name,
        p_last_name,
        p_email,
        p_message,
        CURRENT_TIMESTAMP()
	);
END//

-- Contact ophalen uit de database op basis van het e-mailadres of id
CREATE PROCEDURE get_contact(
    IN p_id INT,
    IN p_email VARCHAR(255)
)
BEGIN
	SELECT * FROM contact
	WHERE `id` = p_id OR `email` = p_email;
END//

-- We verwijderen of updaten de contact NIET uit de database
-- want we willen de contactgegevens bewaren
DELIMITER ;