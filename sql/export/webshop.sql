-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Gegenereerd op: 05 jun 2025 om 08:54
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
DROP DATABASE IF EXISTS `webshop`;
CREATE DATABASE IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `webshop`;

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `add_address` (IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6), IN `p_street_name` VARCHAR(80), IN `p_city` VARCHAR(70))   BEGIN
    INSERT INTO `address` (
        `postal_code`,
        `house_number`,
        `street_name`,
        `city`
    )
    VALUES (
        p_postal_code,
        p_house_number,
        p_street_name,
        p_city
    );

END$$

CREATE PROCEDURE `add_cart_entry` (IN `p_order_number` INT, IN `p_game_id` INT, IN `p_amount` INT, IN `p_when` DATE)   BEGIN
    DECLARE price_snapshot DECIMAL(10,2);

    SELECT
        `price`
    INTO
        price_snapshot
    FROM
        `game`
    WHERE 
        `game_id` = p_game_id;

    UPDATE
        `game` 
    SET
        `left_in_stock` = `left_in_stock` - p_amount 
    WHERE
        `game_id` = p_game_id;

    INSERT INTO cart_entry (
        `order_number`,
        `game_id`, 
        `amount`, 
        `when`, 
        `price_snapshot`
    )
    VALUES (
        p_order_number,
        p_game_id,
        p_amount,
        p_when, -- nullable
        price_snapshot
    );
END$$

CREATE PROCEDURE `add_contact` (IN `p_first_name` VARCHAR(255), IN `p_last_name` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_message` TEXT(3000))   BEGIN
    INSERT INTO contact (
        `first_name`,
        `last_name`,
        `email`,
        `message`,
        `created_at`,
        `status`
    )
    VALUES (
        p_first_name,
        p_last_name,
        p_email,
        p_message,
        CURRENT_TIMESTAMP(),
        0
    );
END$$

CREATE PROCEDURE `add_game` (IN `p_price` DECIMAL(10,2), IN `p_players` INT, IN `p_duration` INT, IN `p_name` VARCHAR(150), IN `p_description` MEDIUMTEXT, IN `p_difficulty` VARCHAR(20), IN `p_left_in_stock` INT)   BEGIN
    INSERT INTO game (`price`, `players`, `duration`, `name`, `description`, `difficulty`,`left_in_stock`)
    VALUES (p_price, p_players, p_duration, p_name, p_description, p_difficulty, p_left_in_stock);

    -- om de id van de toegevoegde game te kunnen ophalen
    SELECT LAST_INSERT_ID() AS id;
END$$

CREATE PROCEDURE `add_order` (IN `p_user_id` INT)   BEGIN
    INSERT INTO `order` (
        `user_id`,
        `date`,
        `status`
    )
    VALUES (
        p_user_id,
         CURRENT_DATE(),
        'PENDING'
    );
END$$

CREATE PROCEDURE `add_review` (IN `p_game_id` INT, IN `p_user_id` INT, IN `p_comment` VARCHAR(280), IN `p_score` TINYINT, IN `p_username` VARCHAR(150))   BEGIN
    INSERT INTO review (
        `game_id`, 
        `user_id`, 
        `comment`, 
        `score`, 
        `posted_on`
    )
    VALUES (
        p_game_id, 
        p_user_id, 
        p_comment, 
        p_score, 
        CURRENT_DATE()
    );
END$$

CREATE PROCEDURE `add_user` (IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6), IN `p_email` VARCHAR(320), IN `p_name` VARCHAR(150), IN `p_role` VARCHAR(15), IN `p_password` VARCHAR(120))   BEGIN
    INSERT INTO user (`postal_code`, `house_number`, `email`, `name`, `role`, `password`)
    VALUES (p_postal_code, p_house_number, p_email, p_name, p_role, p_password);
END$$

CREATE PROCEDURE `add_workshop` (IN `p_game_id` INT, IN `p_min_size` INT, IN `p_max_size` INT, IN `p_duration` INT, IN `p_price` DECIMAL(10,2))   BEGIN
    INSERT INTO `workshop` (
        `game_id`,
        `min_size`,
        `max_size`,
        `duration`,
        `price`
    )
    VALUES (
        p_game_id,
        p_min_size,
        p_max_size,
        p_duration,
        p_price
    );
END$$

CREATE PROCEDURE `add_no_search_result` (IN `p_search_term` VARCHAR(250), `p_user_id` INT, `p_ip_address` VARCHAR(45))   BEGIN
    INSERT INTO `no_search_result` (
        `search_term`,
        `user_id`,
        `ip_address`
    )
    VALUES (
        p_search_term,
        p_user_id,
        p_ip_address
    );
END$$

CREATE PROCEDURE `delete_address` (IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6))   BEGIN
    DELETE FROM `address`
    WHERE
        `postal_code` = p_postal_code
        AND
        `house_number` = p_house_number;
END$$

CREATE PROCEDURE `delete_cart_entry` (IN `p_order_number` INT, IN `p_game_id` INT)   BEGIN
    DECLARE cart_amount INT;

    SELECT
        `amount`
    INTO
        cart_amount
    FROM 
        `cart_entry`
    WHERE
        `order_number` = p_order_number
        AND 
        `game_id` = p_game_id;

    -- update the stock amount
    UPDATE 
        `game`
    SET 
        `left_in_stock` = `left_in_stock` + cart_amount
    WHERE
        `game_id` = p_game_id;

    -- delete the cart entry
    DELETE FROM `cart_entry` 
    WHERE 
        `order_number` = p_order_number 
        AND
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `delete_game` (IN `p_game_id` INT)   BEGIN
    DELETE FROM `game`
    WHERE 
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `delete_user` (IN `p_user_id` INT)   BEGIN
    DELETE FROM
        `user`
    WHERE
        `user_id` = p_user_id;
END$$

CREATE PROCEDURE `delete_workshop` (IN `p_game_id` INT)   BEGIN
    DELETE FROM `workshop`
    WHERE
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `get_address` (IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6))   BEGIN
    SELECT
        `postal_code`,
        `house_number`,
        `street_name`,
        `city`
    FROM
        `address`
    WHERE
        `postal_code` = p_postal_code
        AND
        `house_number` = p_house_number;
END$$

CREATE PROCEDURE `get_avg_review` (IN `p_game_id` INT)   BEGIN
    SELECT
        CAST(AVG(`score`) AS DECIMAL(3,1)) AS score
    FROM
        `review`
    WHERE 
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `get_cart_entries_by_order` (IN `p_order_number` INT)   BEGIN
    SELECT
        `cart_entry`.`order_number`,
        `cart_entry`.`game_id`,
        `cart_entry`.`amount`,
        `cart_entry`.`when`,
        `cart_entry`.`price_snapshot`,
        `game`.`name` AS game_name
    FROM
        `cart_entry`
    INNER JOIN
        `game` ON `cart_entry`.`game_id` = `game`.`game_id`
    WHERE
        `cart_entry`.`order_number` = p_order_number;
END$$

CREATE PROCEDURE `get_contact` (IN `p_id` INT, IN `p_email` VARCHAR(255))   BEGIN
    SELECT *
    FROM `contact`
    WHERE `id` = p_id OR `email` = p_email;
END$$

CREATE PROCEDURE `get_game` (IN `p_game_id` INT)   BEGIN
    SELECT 
        `game_id`,
        `players`,
        `price`,
        `duration`,
        `name`,
        `description`,
        `difficulty`,
        `left_in_stock`
    FROM 
        `game`
    WHERE
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `get_order` (IN `p_order_number` INT)   BEGIN
    SELECT 
        `order_number`,
        `user_id`,
        `date`,
        `comment`,
        `status`
    FROM 
        `order`
    WHERE
        `order_number` = p_order_number;
END$$

CREATE PROCEDURE `get_orders_by_user` (IN `p_user_id` INT)   BEGIN
    SELECT 
        `order_number`,
        `user_id`,
        `date`,
        `comment`,
        `status`
    FROM 
        `order`
    WHERE
        `user_id` = p_user_id
    ORDER BY
        `date` DESC;
END$$

CREATE PROCEDURE `get_reviews_by_game_id` (IN `p_game_id` INT)   BEGIN
    SELECT 
        review.game_id,
        game.name AS game_name,
        review.user_id,
        `user`.`name` AS username,
        review.comment,
        review.score,
        review.posted_on
    FROM 
        review
    INNER JOIN 
        game ON review.game_id = game.game_id
    INNER JOIN 
        `user` ON review.user_id = `user`.`user_id`
    WHERE 
        review.game_id = p_game_id
    ORDER BY 
        review.posted_on DESC;
END$$

CREATE PROCEDURE `get_reviews_by_user` (IN `p_user_name` VARCHAR(150))   BEGIN
    SELECT 
        review.game_id,
        game.name AS game_name,
        review.user_id,
        `user`.`name` AS username,
        review.comment,
        review.score,
        review.posted_on
    FROM 
        `review`
    INNER JOIN 
        `game` ON review.game_id = game.game_id
    INNER JOIN 
        `user` ON review.user_id = `user`.`user_id`
    WHERE
        `review`.`user_id` = p_user_id 
    ORDER BY
        review.posted_on DESC;
END$$

CREATE PROCEDURE `get_unresolved_contacts` ()   BEGIN
    SELECT *
    FROM `contact`
    WHERE `status` < 2; -- 2 == klaar
END$$

CREATE PROCEDURE `get_user` (IN `p_user_id` INT, IN `p_email` VARCHAR(320))   BEGIN
    SELECT 
        `user_id`,
        `postal_code`,
        `house_number`,
        `email`,
        `name`,
        `role`,
        `password`
    FROM
        `user`
    WHERE
        `user_id` = p_user_id
        OR
        `email` = p_email;
END$$

CREATE PROCEDURE `get_workshop` (IN `p_game_id` INT)   BEGIN
    SELECT
        `game_id`,
        `min_size`,
        `max_size`,
        `duration`,
        `price`
    FROM `workshop`
    WHERE
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `update_address` (IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6), IN `p_street_name` VARCHAR(80), IN `p_city` VARCHAR(70))   BEGIN
    UPDATE `address`
    SET
        `postal_code` = COALESCE(p_postal_code, `postal_code`),
        `house_number` = COALESCE(p_house_number, `house_number`),
        `street_name` = COALESCE(p_street_name, `street_name`),
        `city` = COALESCE(p_city, `city`)
    WHERE
        `postal_code` = p_postal_code
        AND
        `house_number` = p_house_number;
END$$

CREATE PROCEDURE `update_cart_entry` (IN `p_order_number` INT, IN `p_game_id` INT, IN `p_new_amount` INT, IN `p_new_when` DATE)   BEGIN
    DECLARE current_amount INT;

    -- fetch the current amount
    SELECT `amount`
    INTO
        current_amount 
    FROM
        `cart_entry` 
    WHERE
        `order_number` = p_order_number 
        AND 
        `game_id` = p_game_id;

    -- update stock
    UPDATE
        `game`
    SET 
        `left_in_stock` = (left_in_stock + current_amount) - p_new_amount
    WHERE `game_id` = p_game_id;

    -- update amount in cart entry
    UPDATE
        `cart_entry`
    SET 
        `amount` = p_new_amount,
        `when` = p_new_when
    WHERE
        `order_number` = p_order_number 
        AND
        `game_id` = p_game_id;
END$$

CREATE PROCEDURE `update_contact` (IN `p_id` INT, IN `p_status` INT)   BEGIN
    UPDATE `contact`
    SET
        `status` = p_status
    WHERE
        `id` = p_id;
END$$

CREATE PROCEDURE `update_game` (IN `p_game_id` INT, IN `p_price` DECIMAL(10,2), IN `p_duration` INT, IN `p_name` VARCHAR(150), IN `p_description` MEDIUMTEXT, IN `p_difficulty` VARCHAR(20), IN `p_left_in_stock` INT)   BEGIN
    UPDATE `game`
    SET 
        `price` = COALESCE(p_price, `price`),
        `duration` = COALESCE(p_duration, `duration`),
        `name` = COALESCE(p_name, `name`),
        `description` = COALESCE(p_description, `description`),
        `difficulty` = COALESCE(p_difficulty, `difficulty`), 
        `left_in_stock` = COALESCE(p_left_in_stock, `left_in_stock`)
    WHERE 
        `game_id` = p_game_id;

END$$

CREATE PROCEDURE `update_order` (IN `p_order_number` INT, IN `p_status` VARCHAR(20), IN `p_comment` VARCHAR(280))   BEGIN
    UPDATE `order`
    SET
        `status` = COALESCE(p_status, `status`),
        `comment` = COALESCE(p_comment, `comment`)
    WHERE `order_number` = p_order_number;
END$$

CREATE PROCEDURE `update_review` (IN `p_game_id` INT, IN `p_user_id` INT, IN `p_new_comment` VARCHAR(280), IN `p_new_score` TINYINT)   BEGIN
    UPDATE 
        `reviews`
    SET
        `comment` = p_new_comment,
        `score` = p_new_score,
        `posted_on` = CURRENT_DATE()
    WHERE
        `game_id` = p_game_id
        AND
        `user_id` = p_user_id;
END$$

CREATE PROCEDURE `update_user` (IN `p_user_id` INT, IN `p_postal_code` VARCHAR(6), IN `p_house_number` VARCHAR(6), IN `p_email` VARCHAR(320), IN `p_name` VARCHAR(150), IN `p_role` VARCHAR(15), IN `p_password` VARCHAR(120))   BEGIN
    -- COALESCE pakt de eerste waarde die niet NULL is
    -- hiermee kan je de procedure aanroepen met NULL-waardes of 
    -- de huidige waarde meegeven, maar het attribuut blijft hetzelfde

    UPDATE user
    SET 
        `postal_code` = COALESCE(p_postal_code, `postal_code`),
        `house_number` = COALESCE(p_house_number, `house_number`),
        `email` = COALESCE(p_email, `email`),
        `name` = COALESCE(p_name, `name`),
        `role` = COALESCE(p_role, `role`),
        `password` = COALESCE(p_password, `password`)
    WHERE
        `user_id` = p_user_id;
END$$

CREATE PROCEDURE `update_workshop` (IN `p_game_id` INT, IN `p_min_size` INT, IN `p_max_size` INT, IN `p_duration` INT, IN `p_price` DECIMAL(10,2))   BEGIN
    UPDATE `workshop`
    SET
        `min_size` = COALESCE(p_min_size, `min_size`),
        `max_size` = COALESCE(p_max_size, `max_size`),
        `duration` = COALESCE(p_duration, `duration`),
        `price` = COALESCE(p_price, `price`)
    WHERE
        `game_id` = p_game_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `address`
--

CREATE TABLE `address` (
  `postal_code` varchar(6) NOT NULL,
  `house_number` varchar(6) NOT NULL,
  `street_name` varchar(80) NOT NULL,
  `city` varchar(70) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `address`
--

INSERT INTO `address` (`postal_code`, `house_number`, `street_name`, `city`) VALUES
('1011AB', '10', 'Damstraat', 'Amsterdam'),
('1012AB', '17', 'Nieuwendijk', 'Amsterdam'),
('1022CD', '15', 'Lijnbaansgracht', 'Amsterdam'),
('1023CD', '23', 'Prinsengracht', 'Amsterdam'),
('1151AA', '30', 'Burgemeester de Ridderstraat', 'Duivendrecht'),
('1152BB', '16', 'Zeeburgerpad', 'Duivendrecht'),
('1222AB', '21', 'Eemnesserweg', 'Hilversum'),
('1223BB', '36', 'Koningsstraat', 'Hilversum'),
('1224AD', '60', 'Sportparklaan', 'Hilversum'),
('1225BC', '33', 'Gijsbrecht van Amstelstraat', 'Hilversum'),
('1501AA', '4', 'Witte de Withstraat', 'Zaandam'),
('1502BB', '7', 'Koogstraat', 'Zaandam'),
('1531AE', '25', 'Dorpsstraat', 'Muiden'),
('1532BA', '9', 'Weg naar de Zuwe', 'Muiden'),
('1941AD', '8', 'Rijnstraat', 'Castricum'),
('1942BC', '12', 'De Koog', 'Castricum'),
('2132BD', '30', 'Herenstraat', 'Haarlem'),
('2133AE', '7', 'Raaks', 'Haarlem'),
('2151BA', '14', 'Leidsevaart', 'Voorschoten'),
('2152CD', '25', 'Plein 1813', 'Voorschoten'),
('2201AB', '1', 'Kerkstraat', 'Noordwijk'),
('2202BB', '3', 'Zeeweg', 'Noordwijk'),
('2281AA', '31', 'Noordeinde', 'Leiden'),
('2282BB', '49', 'Herenstraat', 'Leiden'),
('2351AA', '11', 'Nieuwegracht', 'Leiden'),
('2352BB', '22', 'Breestraat', 'Leiden'),
('2511AB', '78', 'Binnenhof', 'Den Haag'),
('2512CD', '56', 'Lange Poten', 'Den Haag'),
('2513AC', '19', 'Spui', 'Den Haag'),
('2514BB', '60', 'Noordeinde', 'Den Haag'),
('2611AL', '1', 'Delftseplein', 'Delft'),
('2612BA', '5', 'Marktstraat', 'Delft'),
('2721AA', '22', 'Burgemeester Krootstraat', 'Zoetermeer'),
('2722BB', '16', 'Alpenstraat', 'Zoetermeer'),
('2801AB', '40', 'Catherijnestraat', 'Woerden'),
('2802CD', '18', 'Hogeweg', 'Woerden'),
('3031AA', '22', 'Westerstraat', 'Rotterdam'),
('3032BB', '44', 'Blaak', 'Rotterdam'),
('3033AE', '45', 'Coolsingel', 'Rotterdam'),
('3034BB', '34', 'Westblaak', 'Rotterdam'),
('3042BD', '67', 'Eendrachtsweg', 'Rotterdam'),
('3043AC', '82', 'Wijnhaven', 'Rotterdam'),
('3221AA', '20', 'Havenstraat', 'Hellevoetsluis'),
('3222BB', '5', 'Kennedystraat', 'Hellevoetsluis'),
('3401AA', '11', 'Hoogstraat', 'IJsselstein'),
('3402BC', '20', 'Dorpstraat', 'IJsselstein'),
('3511AL', '10', 'Lange Gracht', 'Utrecht'),
('3512AA', '27', 'Vondellaan', 'Utrecht'),
('3513BA', '13', 'Biltstraat', 'Utrecht'),
('3514AB', '22', 'Buurserstraat', 'Utrecht'),
('4401AA', '5', 'Langestraat', 'Kampen'),
('4402BB', '7', 'Stadslaan', 'Kampen'),
('5571AA', '35', 'Markt', 'Bergeijk'),
('5572BB', '25', 'Langstraat', 'Bergeijk'),
('5611AN', '44', 'Wilhelminaplein', 'Eindhoven'),
('5612BA', '65', 'Beukenlaan', 'Eindhoven'),
('5613CC', '11', 'Vonderweg', 'Eindhoven'),
('5614AA', '9', 'Strijp-S', 'Eindhoven'),
('6221AA', '3', 'Maastrichtstraat', 'Maastricht'),
('6222BB', '8', 'Wyckerbrugstraat', 'Maastricht'),
('6401AA', '5', 'Hazenberg', 'Heerlen'),
('6402BB', '8', 'Beatrixstraat', 'Heerlen'),
('6511AN', '89', 'Vijf Meilaan', 'Nijmegen'),
('6512BB', '99', 'Sint Annastraat', 'Nijmegen'),
('6513AD', '67', 'Groenestraat', 'Nijmegen'),
('6514AC', '80', 'Berg en Dalseweg', 'Nijmegen'),
('7101AA', '25', 'Kerkstraat', 'Doetinchem'),
('7102BB', '4', 'Oude IJsselstraat', 'Doetinchem'),
('7311CA', '9', 'Stationsstraat', 'Apeldoorn'),
('7312BB', '10', 'Hoofdstraat', 'Apeldoorn'),
('7411AC', '14', 'Rijksstraatweg', 'Deventer'),
('7411AG', '5', 'Marktstraat', 'Deventer'),
('7412AE', '26', 'Burgemeester Meijerstraat', 'Deventer'),
('7412BC', '12', 'Burgemeester Meijerstraat', 'Deventer'),
('7413AD', '21', 'Keizerstraat', 'Deventer'),
('7414BA', '15', 'Rijksstraatweg', 'Deventer'),
('8011AA', '77', 'Korte Hoogstraat', 'Zwolle'),
('8012BC', '88', 'Pannekoekstraat', 'Zwolle'),
('8501AB', '9', 'Bovenstreek', 'Heerenveen'),
('8502BC', '8', 'Kerkstraat', 'Heerenveen'),
('8531AA', '22', 'Bovenstad', 'Sneek'),
('8532BB', '41', 'Brinkstraat', 'Sneek'),
('8801AB', '27', 'Binnenstad', 'Friesland'),
('8802CC', '13', 'Burgemeester Breukerweg', 'Friesland'),
('8901AB', '8', 'Achter de Hoven', 'Leeuwarden'),
('8902BC', '6', 'Zernikepark', 'Leeuwarden'),
('9031AB', '13', 'Oosterweg', 'Harlingen'),
('9032CD', '57', 'Dijkstraat', 'Harlingen'),
('9151AA', '12', 'Zuidlaan', 'Dokkum'),
('9152BB', '33', 'Hoofdstraat', 'Dokkum'),
('9401AB', '72', 'Herenstraat', 'Assen'),
('9402BC', '20', 'Langestraat', 'Assen'),
('9701AA', '19', 'Grote Markt', 'Groningen'),
('9702BB', '44', 'Hoogstraat', 'Groningen'),
('9721AD', '12', 'Gelkingestraat', 'Groningen'),
('9722CD', '19', 'Oosterhamrikkade', 'Groningen'),
('9741HA', '34', 'Oosterstraat', 'Groningen'),
('9742CC', '56', 'Hoofdstraat', 'Groningen'),
('9743BC', '12', 'Oosterparkwijk', 'Groningen'),
('9744CA', '50', 'Schuitendiep', 'Groningen');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `audit_order`
--

CREATE TABLE `audit_order` (
  `at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_number` int(11) NOT NULL,
  `status_new` varchar(20) DEFAULT NULL,
  `status_old` varchar(20) DEFAULT NULL,
  `comment_new` varchar(280) DEFAULT NULL,
  `comment_old` varchar(280) DEFAULT NULL,
  `who` varchar(40) NOT NULL
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `audit_prices`
--

CREATE TABLE `audit_prices` (
  `at` timestamp NOT NULL DEFAULT current_timestamp(),
  `game_id` int(11) NOT NULL,
  `price_new` decimal(10,2) NOT NULL,
  `price_old` decimal(10,2) NOT NULL,
  `who` varchar(40) NOT NULL,
  `table` varchar(20) NOT NULL
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cart_entry`
--

CREATE TABLE `cart_entry` (
  `order_number` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `when` date DEFAULT NULL,
  `price_snapshot` decimal(10,2) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `cart_entry`
--

INSERT INTO `cart_entry` (`order_number`, `game_id`, `amount`, `when`, `price_snapshot`) VALUES
(1, 21, 3, '2025-03-01', 32.38),
(2, 1, 3, '2025-03-02', 101.51),
(3, 10, 1, '2025-03-03', 40.16),
(4, 8, 2, '2025-03-04', 50.65),
(5, 7, 3, '2025-03-05', 42.11),
(6, 15, 2, '2025-03-06', 90.06),
(6, 23, 3, '2025-03-06', 138.80),
(7, 24, 2, '2025-03-07', 157.28),
(8, 14, 1, '2025-03-08', 27.53),
(9, 13, 3, '2025-03-09', 208.31),
(10, 7, 1, '2025-03-10', 42.11),
(10, 25, 2, '2025-03-10', 178.47),
(11, 12, 3, '2025-03-11', 102.43),
(12, 4, 2, '2025-03-12', 127.66),
(12, 30, 1, '2025-03-12', 125.73),
(13, 28, 3, '2025-03-13', 156.64),
(14, 15, 2, '2025-03-14', 90.06),
(15, 18, 1, '2025-03-15', 244.16),
(16, 17, 3, '2025-03-16', 247.21),
(17, 5, 3, '2025-03-17', 46.18),
(18, 12, 3, '2025-03-18', 102.43),
(18, 29, 2, '2025-03-18', 114.20),
(19, 6, 1, '2025-03-19', 42.92),
(20, 3, 1, '2025-03-20', 58.66),
(20, 9, 2, '2025-03-20', 67.76),
(21, 11, 3, '2025-03-21', 209.24),
(22, 27, 1, '2025-03-22', 163.94),
(23, 3, 2, '2025-03-23', 58.66),
(24, 2, 3, '2025-03-24', 217.43),
(25, 8, 2, '2025-03-25', 50.65),
(25, 22, 1, '2025-03-25', 42.62),
(26, 19, 2, '2025-03-26', 176.73),
(27, 26, 3, '2025-03-27', 92.80),
(28, 16, 2, '2025-03-28', 230.80),
(29, 20, 1, '2025-03-29', 39.82),
(30, 4, 3, '2025-03-30', 127.66),
(30, 19, 2, '2025-03-30', 176.73),
(31, 30, 2, '2025-03-31', 125.73),
(32, 5, 3, '2025-04-01', 46.18),
(32, 23, 1, '2025-04-01', 138.80),
(33, 21, 3, '2025-04-02', 32.38),
(34, 8, 2, '2025-04-03', 50.65),
(35, 1, 1, '2025-04-04', 101.51),
(35, 10, 3, '2025-04-04', 40.16),
(36, 14, 1, '2025-04-05', 27.53),
(37, 7, 3, '2025-04-06', 42.11),
(38, 12, 2, '2025-04-07', 102.43),
(39, 9, 1, '2025-04-08', 67.76),
(40, 5, 3, '2025-04-09', 46.18),
(40, 27, 1, '2025-04-09', 163.94),
(41, 1, 2, '2025-04-10', 101.51),
(41, 9, 1, '2025-04-10', 67.76),
(42, 18, 1, '2025-04-11', 244.16),
(43, 17, 3, '2025-04-12', 247.21),
(44, 6, 2, '2025-04-13', 42.92),
(45, 13, 1, '2025-04-14', 208.31),
(46, 14, 2, '2025-04-15', 27.53),
(46, 16, 3, '2025-04-15', 230.80),
(47, 3, 2, '2025-04-16', 58.66),
(48, 26, 3, '2025-04-17', 92.80),
(49, 2, 1, '2025-04-18', 217.43),
(50, 22, 2, '2025-04-19', 42.62),
(50, 28, 2, '2025-04-19', 156.64);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NOT NULL
);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `game`
--

CREATE TABLE `game` (
  `game_id` int(11) NOT NULL,
  `players` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int(11) DEFAULT NULL CHECK (`duration` > 0),
  `name` varchar(150) NOT NULL,
  `description` mediumtext NOT NULL,
  `difficulty` varchar(20) NOT NULL,
  `left_in_stock` int(11) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `game`
--

INSERT INTO `game` (`game_id`, `players`, `price`, `duration`, `name`, `description`, `difficulty`, `left_in_stock`) VALUES
(1, 2, 101.51, 60, 'Medieval Mayhem: The Final 2', 'Een actievolle game boordevol explosies', 'Moeilijk', 13),
(2, 4, 217.43, 360, 'Epic Quest 2', 'Een strategisch spel waarbij elke zet telt', 'Matig', 12),
(3, 8, 58.66, 630, 'Superhero Showdown: The Return 2', 'Een sportgame om je favoriete sport te spelen', 'Matig', 13),
(4, 10, 127.66, 600, 'Cyberpunk City', 'Een fantasievolle game met magische wezens', 'Uitdagend', -1),
(5, 8, 46.18, 420, 'Crime Fighter Chronicles', 'Een survival game waarbij je moet zien te overleven', 'Moeilijk', -4),
(6, 6, 42.92, 630, 'Survival Saga', 'Een educatief spel om nieuwe vaardigheden te leren', 'Moeilijk', 5),
(7, 10, 42.11, 540, 'Puzzle Paradise', 'Een platform game vol uitdagende levels', 'Matig', 1),
(8, 4, 50.65, 270, 'Survival Saga 2', 'Een race tegen de klok om de wereld te redden', 'Moeilijk', -5),
(9, 6, 67.76, 630, 'Magic Kingdom', 'Een science fiction game met buitenaardse wezens', 'Uitdagend', 1),
(10, 6, 40.16, 510, 'Wild West Adventure', 'Een educatieve game om nieuwe kennis op te doen', 'Matig', 2),
(11, 8, 209.24, 660, 'Space Odyssey', 'Een historische game die je terugbrengt naar het verleden', 'Uitdagend', -3),
(12, 10, 102.43, 630, 'Survival Saga: Lost Age', 'Een retro game met een moderne twist', 'Moeilijk', 9),
(13, 8, 208.31, 420, 'Dragon Quest', 'Een educatief spel om nieuwe vaardigheden te leren', 'Matig', 13),
(14, 8, 27.53, 180, 'Battle Royale: 2', 'Een actievolle game boordevol explosies', 'Gemakkelijk', 16),
(15, 2, 90.06, 690, 'Fantasy Quest', 'Een detective spel waarbij je een moord moet oplossen', 'Gemakkelijk', -3),
(16, 4, 230.80, 420, 'Mythical Creatures: Blood Line', 'Een spannend avontuur in een magische wereld', 'Matig', 12),
(17, 4, 247.21, 510, 'Alien Invasion', 'Een science game om de wereld om ons heen te ontdekken', 'Uitdagend', 14),
(18, 8, 244.16, 390, 'Superhero Showdown', 'Een simulatiegame waarbij je een bedrijf moet runnen', 'Gemakkelijk', -2),
(19, 4, 176.73, 570, 'Cyberpunk City 2', 'Een science game om de wereld om ons heen te ontdekken', 'Uitdagend', 3),
(20, 6, 39.82, 540, 'Haunted House Horror', 'Een educatief spel om nieuwe vaardigheden te leren', 'Matig', 11),
(21, 6, 32.38, 240, 'Magic Kingdom 2', 'Een vechtspel met epische gevechten', 'Gemakkelijk', -1),
(22, 8, 42.62, 150, 'Pirate\'s Plunder', 'Een race tegen de klok om de wereld te redden', 'Uitdagend', 0),
(23, 2, 138.80, 210, 'Wild West Adventure 2', 'Een spannend avontuur in een magische wereld', 'Moeilijk', 16),
(24, 4, 157.28, 390, 'Pirate\'s Plunder 2', 'Een role-playing game met een meeslepend verhaal', 'Moeilijk', 14),
(25, 10, 178.47, 210, 'Mythical Creatures', 'Een multiplayer game om samen met vrienden te spelen', 'Gemakkelijk', 12),
(26, 4, 92.80, 600, 'Haunted House Horror 2', 'Een retro game met een moderne twist', 'Gemakkelijk', -5),
(27, 6, 163.94, 300, 'Ancient Ruins', 'Een sprookjesachtig avontuur met betoverende graphics', 'Moeilijk', 10),
(28, 2, 156.64, 300, 'Fantasy Quest 2', 'Een science fiction game met buitenaardse wezens', 'Moeilijk', 2),
(29, 6, 114.20, 600, 'Battle Royale', 'Een horror game die je de rillingen bezorgt', 'Matig', 0),
(30, 10, 125.73, 270, 'Galactic Conquest', 'Een muziekgame waarbij je moet dansen op de maat', 'Moeilijk', 9);

--
-- Triggers `game`
--
DELIMITER $$
CREATE TRIGGER `audit_game_price_update` AFTER UPDATE ON `game` FOR EACH ROW BEGIN
    IF NEW.price <> OLD.price THEN
        INSERT INTO `audit_prices` (
            `price_new`,
            `price_old`,
            `who`,
            `table`,
            `game_id`
        )
        VALUES (
            new.price,
            old.price,
            SESSION_USER(), -- de database-gebruiker die de trigger triggert
            'game',
            old.game_id
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order`
--

CREATE TABLE `order` (
  `order_number` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT curdate(),
  `comment` text DEFAULT NULL,
  `status` varchar(20) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `order`
--

INSERT INTO `order` (`order_number`, `user_id`, `date`, `comment`, `status`) VALUES
(1, 16, '2025-03-24', 'De bestelling wordt morgen bezorgd, past niet door de brievenbus', 'UNDERWAY'),
(2, 17, '2025-03-25', 'Dit bordspel wordt later geleverd vanwege vertraging bij de leverancier', 'DELIVERED'),
(3, 18, '2025-03-26', 'Bestelling is onderweg, verwacht levering binnen 2 dagen', 'UNDERWAY'),
(4, 19, '2025-03-27', 'Opmerking: pakket is te groot voor de brievenbus', 'UNDERWAY'),
(5, 20, '2025-03-28', 'Bezorging wordt een dag later verwacht, de koerier heeft vertraging', 'UNDERWAY'),
(6, 21, '2025-03-29', 'Levering van het kaartspel was vertraagd door een logisitieke fout', 'DELIVERED'),
(7, 22, '2025-03-30', 'Opmerking: pakket past niet door de brievenbus, moet worden opgehaald bij postkantoor', 'UNDERWAY'),
(8, 23, '2025-03-31', 'De levering zal morgen plaatsvinden, er is een lichte vertraging', 'UNDERWAY'),
(9, 24, '2025-03-01', 'De levering van het bordspel is vertraagd door een voorraadprobleem', 'UNDERWAY'),
(10, 25, '2025-03-02', 'Het kaartspel wordt op de afgesproken datum geleverd, geen vertraging', 'UNDERWAY'),
(11, 26, '2025-03-03', 'Bestelling is nu onderweg, wordt de volgende dag afgeleverd', 'DELIVERED'),
(12, 27, '2025-03-03', 'Er is een probleem met de levering, de bezorging zal een dag later zijn', 'UNDERWAY'),
(13, 28, '2025-03-03', 'Pakket is onderweg, verwacht een levering binnen 2 dagen', 'UNDERWAY'),
(14, 29, '2025-03-06', 'De bestelling is vertraagd vanwege een probleem bij de leverancier', 'DELIVERED'),
(15, 30, '2025-03-07', 'Het spel wordt deze week nog geleverd, verwacht enige vertraging', 'UNDERWAY'),
(16, 31, '2025-03-08', 'Verwachting is dat het spel morgen aankomt, er zijn geen vertragingen', 'UNDERWAY'),
(17, 32, '2025-03-09', 'Vertraagde levering door technische problemen, pakket komt later', 'DELIVERED'),
(18, 33, '2025-03-10', 'De bestelling is onderweg, verwacht binnen 3 dagen', 'UNDERWAY'),
(19, 34, '2025-03-11', 'Levering zal een dag later zijn door een storing bij de koerier', 'UNDERWAY'),
(20, 35, '2025-03-12', 'Het pakket is onderweg, wordt op 14 april geleverd', 'DELIVERED'),
(21, 36, '2025-03-13', NULL, 'UNDERWAY'),
(22, 37, '2025-03-14', NULL, 'DELIVERED'),
(23, 38, '2025-03-15', NULL, 'UNDERWAY'),
(24, 39, '2025-03-16', NULL, 'UNDERWAY'),
(25, 40, '2025-03-17', NULL, 'UNDERWAY'),
(26, 41, '2025-03-18', NULL, 'DELIVERED'),
(27, 42, '2025-03-19', NULL, 'UNDERWAY'),
(28, 43, '2025-03-20', NULL, 'UNDERWAY'),
(29, 44, '2025-03-21', NULL, 'UNDERWAY'),
(30, 45, '2025-03-22', NULL, 'DELIVERED'),
(31, 46, '2025-03-23', NULL, 'UNDERWAY'),
(32, 47, '2025-03-24', NULL, 'DELIVERED'),
(33, 48, '2025-03-25', NULL, 'UNDERWAY'),
(34, 49, '2025-03-26', NULL, 'UNDERWAY'),
(35, 50, '2025-03-27', NULL, 'DELIVERED'),
(36, 51, '2025-03-28', NULL, 'UNDERWAY'),
(37, 52, '2025-03-29', NULL, 'UNDERWAY'),
(38, 53, '2025-03-30', NULL, 'DELIVERED'),
(39, 54, '2025-03-01', NULL, 'UNDERWAY'),
(40, 55, '2025-03-02', NULL, 'DELIVERED'),
(41, 56, '2025-03-03', NULL, 'UNDERWAY'),
(42, 57, '2025-03-03', NULL, 'UNDERWAY'),
(43, 58, '2025-03-03', NULL, 'DELIVERED'),
(44, 59, '2025-03-06', NULL, 'UNDERWAY'),
(45, 60, '2025-03-07', NULL, 'DELIVERED'),
(46, 61, '2025-03-08', NULL, 'UNDERWAY'),
(47, 62, '2025-03-09', NULL, 'DELIVERED'),
(48, 63, '2025-03-10', NULL, 'UNDERWAY'),
(49, 64, '2025-03-11', NULL, 'DELIVERED'),
(50, 65, '2025-03-12', NULL, 'UNDERWAY');

--
-- Triggers `order`
--
DELIMITER $$
CREATE TRIGGER `audit_order_update` AFTER UPDATE ON `order` FOR EACH ROW BEGIN
    IF NEW.status <> OLD.status OR NEW.comment <> OLD.comment THEN
        INSERT INTO `audit_order` (
            `status_old`,
            `status_new`,
            `comment_old`,
            `comment_new`,
            `who`
        )
        VALUES (
            OLD.status,
            NEW.status,
            OLD.comment,
            NEW.comment,
            SESSION_USER() -- de database-gebruiker die de trigger triggert
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `review`
--

CREATE TABLE `review` (
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `score` tinyint(4) NOT NULL CHECK (`score` > 0 and `score` <= 10),
  `posted_on` date DEFAULT curdate()
);

--
-- Gegevens worden geëxporteerd voor tabel `review`
--

INSERT INTO `review` (`game_id`, `user_id`, `comment`, `score`, `posted_on`) VALUES
(1, 16, 'Dit is het beste spel dat ik ooit heb gespeeld', 2, '2025-03-13'),
(1, 55, 'De community rondom dit spel is erg vriendelijk', 9, '2025-03-04'),
(2, 25, 'De gameplay is verslavend', 2, '2025-03-13'),
(2, 79, 'De updates houden het spel fris en interessant', 1, '2025-03-16'),
(3, 88, 'Ik ben verslaafd aan het verzamelen van in-game items', 8, '2025-03-03'),
(4, 20, 'Ik kan niet genoeg krijgen van dit spel', 6, '2025-03-05'),
(4, 22, 'De strategieën die je kunt gebruiken zijn eindeloos', 5, '2025-03-26'),
(5, 44, 'De prijs-kwaliteitverhouding is uitstekend', 1, '2025-03-20'),
(5, 55, 'De prijs-kwaliteitverhouding is uitstekend', 2, '2025-03-12'),
(5, 66, 'Dit spel is een meesterwerk', 4, '2025-03-24'),
(6, 66, 'De community rondom dit spel is erg vriendelijk', 5, '2025-03-01'),
(7, 92, 'De muziek is fantastisch', 9, '2025-03-06'),
(8, 81, 'De controls zijn soepel en responsief', 8, '2025-03-13'),
(8, 88, 'Ik ben onder de indruk van de details in dit spel', 10, '2025-03-19'),
(9, 99, 'Ik heb al mijn vrienden aangeraden om dit spel te spelen', 2, '2025-03-07'),
(18, 62, 'De community rondom dit spel is erg vriendelijk', 5, '2025-03-07'),
(19, 44, 'Dit spel is geweldig!', 8, '2025-03-07'),
(21, 34, 'De multiplayer-modus is geweldig', 9, '2025-03-01'),
(22, 23, 'Dit spel heeft mijn verwachtingen overtroffen', 6, '2025-03-05'),
(22, 44, 'De prijs-kwaliteitverhouding is uitstekend', 3, '2025-03-05'),
(26, 35, 'De graphics zijn verbluffend', 4, '2025-03-08'),
(26, 65, 'Ik ben onder de indruk van de details in dit spel', 10, '2025-03-01'),
(29, 44, 'Ik ben een fan van de ontwikkelaars geworden', 2, '2025-03-02'),
(30, 30, 'De strategieën die je kunt gebruiken zijn eindeloos', 6, '2025-03-03'),
(30, 77, 'Ik ben trots op mijn prestaties in dit spel', 3, '2025-03-10');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `postal_code` varchar(6) DEFAULT NULL,
  `house_number` varchar(6) DEFAULT NULL,
  `email` varchar(320) NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` varchar(15) NOT NULL,
  `password` varchar(120) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`user_id`, `postal_code`, `house_number`, `email`, `name`, `role`, `password`) VALUES
(1, '1011AB', '10', 'jansen@connect-play.nl', 'Jansen', 'Administrator', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(2, '1022CD', '15', 'devries@connect-play.nl', 'De Vries', 'Administrator', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(3, '3031AA', '22', 'bakker@connect-play.nl', 'Bakker', 'Administrator', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(4, '3032BB', '44', 'visser@connect-play.nl', 'Visser', 'Administrator', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(5, '2511AB', '78', 'smit@connect-play.nl', 'Smit', 'Administrator', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(6, '2512CD', '56', 'meijer@connect-play.nl', 'Meijer', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(7, '7411AG', '5', 'vandijk@connect-play.nl', 'Van Dijk', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(8, '7412BC', '12', 'mulder@connect-play.nl', 'Mulder', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(9, '9741HA', '34', 'deboer@connect-play.nl', 'De Boer', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(10, '9742CC', '56', 'hendriks@connect-play.nl', 'Hendriks', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(11, '6511AN', '89', 'peters@connect-play.nl', 'Peters', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(12, '6512BB', '99', 'vandermeer@connect-play.nl', 'Van der Meer', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(13, '3511AL', '10', 'vermeulen@connect-play.nl', 'Vermeulen', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(14, '3512AA', '27', 'vandenberg@connect-play.nl', 'Van den Berg', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(15, '5611AN', '44', 'koster@connect-play.nl', 'Koster', 'Employee', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(16, '5612BA', '65', 'brouwer@gmail.com', 'Brouwer', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(17, '1222AB', '21', 'dejong@gmail.com', 'De Jong', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(18, '1223BB', '36', 'janssen@gmail.com', 'Janssen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(19, '8011AA', '77', 'schouten@gmail.com', 'Schouten', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(20, '8012BC', '88', 'vos@gmail.com', 'Vos', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(21, '7311CA', '9', 'wolters@gmail.com', 'Wolters', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(22, '7312BB', '10', 'vanleeuwen@gmail.com', 'Van Leeuwen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(23, '9721AD', '12', 'hermans@gmail.com', 'Hermans', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(24, '9722CD', '19', 'kuipers@gmail.com', 'Kuipers', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(25, '2611AL', '1', 'sanders@gmail.com', 'Sanders', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(26, '2612BA', '5', 'veldman@gmail.com', 'Veldman', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(27, '6221AA', '3', 'vanderlinden@gmail.com', 'Van der Linden', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(28, '6222BB', '8', 'koenen@gmail.com', 'Koenen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(29, '7411AC', '14', 'degroot@gmail.com', 'De Groot', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(30, '7412AE', '26', 'bakhuizen@gmail.com', 'Bakhuizen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(31, '2281AA', '31', 'rijken@gmail.com', 'Rijken', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(32, '2282BB', '49', 'vanderlaan@gmail.com', 'Van der Laan', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(33, '3042BD', '67', 'timmermans@gmail.com', 'Timmermans', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(34, '3043AC', '82', 'teixeira@gmail.com', 'Teixeira', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(35, '9031AB', '13', 'haring@gmail.com', 'Haring', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(36, '9032CD', '57', 'lammers@gmail.com', 'Lammers', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(37, '8531AA', '22', 'dijkstra@gmail.com', 'Dijkstra', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(38, '8532BB', '41', 'vanharen@gmail.com', 'Van Haren', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(39, '9151AA', '12', 'delange@gmail.com', 'De Lange', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(40, '9152BB', '33', 'kloosterman@gmail.com', 'Kloosterman', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(41, '1531AE', '25', 'vanginkel@gmail.com', 'Van Ginkel', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(42, '1532BA', '9', 'kooijman@gmail.com', 'Kooijman', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(43, '2151BA', '14', 'vanwijk@gmail.com', 'Van Wijk', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(44, '2152CD', '25', 'wijnandts@gmail.com', 'Wijnandts', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(45, '2132BD', '30', 'vrijdag@gmail.com', 'Vrijdag', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(46, '2133AE', '7', 'vanderwal@gmail.com', 'Van der Wal', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(47, '3401AA', '11', 'vanderstelt@gmail.com', 'Van der Stelt', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(48, '3402BC', '20', 'hoogendoorn@gmail.com', 'Hoogendoorn', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(49, '2721AA', '22', 'bulten@gmail.com', 'Bulten', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(50, '2722BB', '16', 'vandervelden@gmail.com', 'Van der Velden', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(51, '1941AD', '8', 'visscher@gmail.com', 'Visscher', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(52, '1942BC', '12', 'smit2@gmail.com', 'Smit', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(53, '2201AB', '1', 'zwart@gmail.com', 'Zwart', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(54, '2202BB', '3', 'koster2@gmail.com', 'Koster', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(55, '7101AA', '25', 'donkers@gmail.com', 'Donkers', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(56, '7102BB', '4', 'gerritsen@gmail.com', 'Gerritsen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(57, '2801AB', '40', 'vrieze@gmail.com', 'Vrieze', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(58, '2802CD', '18', 'tillema@gmail.com', 'Tillema', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(59, '9401AB', '72', 'oosterhuis@gmail.com', 'Oosterhuis', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(60, '9402BC', '20', 'dehaan@gmail.com', 'De Haan', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(61, '8801AB', '27', 'langerak@gmail.com', 'Langerak', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(62, '8802CC', '13', 'heijmans@gmail.com', 'Heijmans', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(63, '4401AA', '5', 'zijlstra@gmail.com', 'Zijlstra', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(64, '4402BB', '7', 'bakker2@gmail.com', 'Bakker', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(65, '8901AB', '8', 'versteeg@gmail.com', 'Versteeg', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(66, '8902BC', '6', 'hoekstra@gmail.com', 'Hoekstra', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(67, '5571AA', '35', 'struik@gmail.com', 'Struik', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(68, '5572BB', '25', 'roos@gmail.com', 'Roos', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(69, '1151AA', '30', 'devos@gmail.com', 'De Vos', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(70, '1152BB', '16', 'boersma@gmail.com', 'Boersma', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(71, '2351AA', '11', 'wieringa@hotmail.com', 'Wieringa', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(72, '2352BB', '22', 'blom@hotmail.com', 'Blom', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(73, '9701AA', '19', 'smits@hotmail.com', 'Smits', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(74, '9702BB', '44', 'boogaard@hotmail.com', 'Boogaard', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(75, '6401AA', '5', 'vanderpol@hotmail.com', 'Van der Pol', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(76, '6402BB', '8', 'vandermolen@hotmail.com', 'Van der Molen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(77, '3221AA', '20', 'nijkamp@hotmail.com', 'Nijkamp', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(78, '3222BB', '5', 'kooistra@hotmail.com', 'Kooistra', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(79, '1501AA', '4', 'brinks@hotmail.com', 'Brinks', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(80, '1502BB', '7', 'jager@hotmail.com', 'Jager', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(81, '8501AB', '9', 'harmsen@hotmail.com', 'Harmsen', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(82, '8502BC', '8', 'wouters@hotmail.com', 'Wouters', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(83, '1012AB', '17', 'jvdsluis@gmail.com', 'Jan van de Sluis', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(84, '1023CD', '23', 'vandersluis@hotmail.com', 'Van der Sluis', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(85, '3033AE', '45', 'juanperez@hotmail.com', 'Juan Perez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(86, '3034BB', '34', 'mariahernandez@hotmail.com', 'Maria Hernandez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(87, '2513AC', '19', 'huseyinaydemir@hotmail.com', 'Huseyin Aydemir', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(88, '2514BB', '60', 'aysekarabulut@hotmail.com', 'Ayse Karabulut', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(89, '7413AD', '21', 'pedrolopez@hotmail.com', 'Pedro Lopez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(90, '7414BA', '15', 'antoniofernandez@hotmail.com', 'Antonio Fernandez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(91, '9743BC', '12', 'gokhanekin@hotmail.com', 'Gokhan Ekin', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(92, '9744CA', '50', 'sylviagonzalez@hotmail.com', 'Sylvia Gonzalez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(93, '6513AD', '67', 'mustafagul@hotmail.com', 'Mustafa Gul', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(94, '6514AC', '80', 'elizabethmiller@hotmail.com', 'Elizabeth Miller', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(95, '3513BA', '13', 'serhattokmak@hotmail.com', 'Serhat Tokmak', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(96, '3514AB', '22', 'luisramirez@hotmail.com', 'Luis Ramurez', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(97, '5613CC', '11', 'yildizaltan@hotmail.com', 'Yildiz Altan', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(98, '5614AA', '9', 'dianesmith@hotmail.com', 'Diane Smith', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(99, '1224AD', '60', 'omerakkus@hotmail.com', 'Omer Akkus', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy'),
(100, '1225BC', '33', 'mariolima@hotmail.com', 'Mario Lima', 'Customer', '$2a$04$uXjvjpcjlsjs92kimygEMOJnj1DA/lMX5KHVFI4b0dNURVr4jonRy');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `workshop`
--

CREATE TABLE `workshop` (
  `game_id` int(11) NOT NULL,
  `min_size` int(11) NOT NULL,
  `max_size` int(11) NOT NULL,
  `duration` int(11) NOT NULL CHECK (`duration` > 0),
  `price` decimal(10,2) NOT NULL
);

--
-- Gegevens worden geëxporteerd voor tabel `workshop`
--

INSERT INTO `workshop` (`game_id`, `min_size`, `max_size`, `duration`, `price`) VALUES
(1, 10, 30, 120, 364.38),
(2, 16, 24, 180, 256.87),
(3, 12, 24, 120, 258.36),
(4, 16, 24, 240, 391.49),
(5, 16, 28, 120, 273.31),
(6, 12, 28, 180, 349.96),
(7, 10, 24, 180, 236.86),
(8, 16, 26, 300, 157.60),
(9, 16, 32, 300, 280.78),
(10, 8, 24, 300, 363.79),
(11, 10, 26, 300, 194.74),
(12, 16, 32, 180, 226.91),
(13, 8, 26, 240, 332.62),
(14, 14, 32, 180, 202.11),
(15, 16, 24, 300, 336.48),
(16, 14, 32, 180, 136.61),
(17, 12, 18, 180, 234.50),
(18, 8, 16, 180, 129.29),
(19, 10, 28, 300, 154.76),
(20, 12, 26, 120, 370.99);

--
-- Triggers `workshop`
--
DELIMITER $$
CREATE TRIGGER `audit_workshop_price_update` AFTER UPDATE ON `workshop` FOR EACH ROW BEGIN
    IF NEW.price <> OLD.price THEN
        INSERT INTO `audit_prices` (
            `price_new`,
            `price_old`,
            `who`,
            `table`,
            `game_id`
        )
        VALUES (
            NEW.price,
            OLD.price,
            SESSION_USER(), -- de database-gebruiker die de trigger triggert
            'workshop',
            old.game_id
        );
    END IF;

END
$$
DELIMITER ;
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `no_search_result`
--

CREATE TABLE `no_search_result` (
  `id` int(11) NOT NULL,
  `search_term` varchar(150) NOT NULL,
  `user_id` int(11),
  `ip_address` varchar(45) NOT NULL,
  `posted_on` DATETIME DEFAULT CURRENT_TIMESTAMP
);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`postal_code`,`house_number`);

--
-- Indexen voor tabel `cart_entry`
--
ALTER TABLE `cart_entry`
  ADD PRIMARY KEY (`order_number`,`game_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexen voor tabel `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`game_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexen voor tabel `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`game_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `postal_code` (`postal_code`,`house_number`);

--
-- Indexen voor tabel `workshop`
--
ALTER TABLE `workshop`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexen voor tabel `no_search_result`
--
ALTER TABLE `no_search_result`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT voor een tabel `order`
--
ALTER TABLE `order`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT voor een tabel `no_result_searches`
--
ALTER TABLE `no_search_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `cart_entry`
--
ALTER TABLE `cart_entry`
  ADD CONSTRAINT `cart_entry_ibfk_1` FOREIGN KEY (`order_number`) REFERENCES `order` (`order_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_entry_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `game` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`postal_code`,`house_number`) REFERENCES `address` (`postal_code`, `house_number`) ON DELETE SET NULL;

--
-- Beperkingen voor tabel `workshop`
--
ALTER TABLE `workshop`
  ADD CONSTRAINT `workshop_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `game` (`game_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
