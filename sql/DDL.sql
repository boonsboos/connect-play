-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 17 mrt 2025 om 21:26
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

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

CREATE TABLE audit_order (
    `at` TIMESTAMP NOT NULL DEFAULT NOW(),
    `order_number` INT NOT NULL,
    `status_new` VARCHAR(20),
    `status_old` VARCHAR(20),
    `comment_new` VARCHAR(280),
    `comment_old` VARCHAR(280),
    `who` VARCHAR(40) NOT NULL
);

CREATE TABLE audit_prices (
    `at` TIMESTAMP NOT NULL DEFAULT NOW(),
    `game_id` INT NOT NULL,
    `price_new` DECIMAL(10,2) NOT NULL,
    `price_old` DECIMAL(10,2) NOT NULL,
    `who` VARCHAR(40) NOT NULL,
    `table` VARCHAR(20) NOT NULL
);

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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cart_entry`
--

CREATE TABLE `cart_entry` (
  `order_number` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `when` date,
  `price_snapshot` decimal(10,2) NOT NULL
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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `order`
--

CREATE TABLE `order` (
  `order_number` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT CURRENT_DATE(),
  `comment` text DEFAULT NULL,
  `status` varchar(20) NOT NULL
);

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

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `postal_code` varchar(6) DEFAULT NULL,
  `house_number` varchar(6) DEFAULT NULL,
  `email` varchar(320) UNIQUE NOT NULL,
  `name` varchar(150) NOT NULL,
  `role` varchar(15) NOT NULL,
  `password` varchar(120) NOT NULL
);

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
  ADD KEY `postal_code` (`postal_code`,`house_number`);

--
-- Indexen voor tabel `workshop`
--
ALTER TABLE `workshop`
  ADD PRIMARY KEY (`game_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `game`
--
ALTER TABLE `game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `order`
--
ALTER TABLE `order`
  MODIFY `order_number` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

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
DELIMITER //

-- adres toevoegen
-- Author: JD
CREATE PROCEDURE add_address(
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6),
    IN p_street_name VARCHAR(80),
    IN p_city VARCHAR(70)
)
BEGIN
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

END //

-- adres bijwerken
-- Author: JD
CREATE PROCEDURE update_address(
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6),
    IN p_street_name VARCHAR(80),
    IN p_city VARCHAR(70)
)
BEGIN
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
END //

-- adres verwijderen
-- Author: JD
CREATE PROCEDURE delete_address(
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6)
)
BEGIN
    DELETE FROM `address`
    WHERE
        `postal_code` = p_postal_code
        AND
        `house_number` = p_house_number;
END //

-- adres ophalen
-- Author: JD
CREATE PROCEDURE get_address(
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6)
)
BEGIN
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
END //
DELIMITER ;
DELIMITER //

-- Vullen cart_entry
-- Author: KB, JD
CREATE PROCEDURE add_cart_entry(
    IN p_order_number INT,
    IN p_game_id INT,
    IN p_amount INT,
    IN p_when DATE
)
BEGIN
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
END//

-- Updaten cart_entry
-- Author: KB, JD
CREATE PROCEDURE update_cart_entry(
    IN p_order_number INT,
    IN p_game_id INT,
    IN p_new_amount INT,
    IN p_new_when DATE
)
BEGIN
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
END //


-- Verwijderen cart_entry
-- Author: KB, JD
CREATE PROCEDURE delete_cart_entry(
    IN p_order_number INT,
    IN p_game_id INT
)
BEGIN
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
END //

DELIMITER ;
-- Game toevoegen
-- Author: KB, JD
DELIMITER //

CREATE PROCEDURE add_game(
    IN p_price DECIMAL(10,2),
    IN p_players INT,
    IN p_duration INT,
    IN p_name VARCHAR(150),
    IN p_description MEDIUMTEXT,
    IN p_difficulty VARCHAR(20),
    IN p_left_in_stock INT
)
BEGIN
    INSERT INTO game (`price`, `players`, `duration`, `name`, `description`, `difficulty`,`left_in_stock`)
    VALUES (p_price, p_players, p_duration, p_name, p_description, p_difficulty, p_left_in_stock);

    -- om de id van de toegevoegde game te kunnen ophalen
    SELECT LAST_INSERT_ID() AS id;
END //

DELIMITER ;

-- Aanroepen (Uit comment halen)
-- CALL add_game(`39,99`, `60`, `Risk`, `Een strategiespel om de wereld over te nemen`, `Gemiddeld`, `5`);

-- Game bijwerken
-- Author: JD
DELIMITER //

CREATE PROCEDURE update_game(
    IN p_game_id INT,
    IN p_price DECIMAL(10,2),
    IN p_duration INT,
    IN p_name VARCHAR(150),
    IN p_description MEDIUMTEXT,
    IN p_difficulty VARCHAR(20),
    IN p_left_in_stock INT
)
BEGIN
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

END //

DELIMITER ;

-- Game ophalen
-- Author: JD
DELIMITER //

CREATE PROCEDURE get_game(
    IN p_game_id INT
)
BEGIN
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
END //

DELIMITER ;

-- Game verwijderen
-- Author: JD
DELIMITER //

CREATE PROCEDURE delete_game(
    IN p_game_id INT
)
BEGIN
    DELETE FROM `game`
    WHERE 
        `game_id` = p_game_id;
END //

DELIMITER ;
-- Order aanmaken en het ordernummer teruggeven
-- Author: JD
DELIMITER //

CREATE PROCEDURE add_order(
    IN p_user_id INT
)
BEGIN
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
END //


-- Order updaten
-- Author: JD
CREATE PROCEDURE update_order(
    IN p_order_number INT,
    IN p_status VARCHAR(20),
    IN p_comment VARCHAR(280)
)
BEGIN
    UPDATE `order`
    SET
        `status` = COALESCE(p_status, `status`),
        `comment` = COALESCE(p_comment, `comment`)
    WHERE `order_number` = p_order_number;
END //


-- Order ophalen
-- Author: JD
CREATE PROCEDURE get_order(
    IN p_order_number INT
)
BEGIN
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
END //

-- We verwijderen NOOIT orders.
DELIMITER;
DELIMITER //

-- Review toevoegen
-- Author: KB
CREATE PROCEDURE add_review(
    IN p_game_id INT,
    IN p_user_id INT,
    IN p_comment VARCHAR(280),
    IN p_score TINYINT,
    IN p_username VARCHAR(150)
)
BEGIN
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
END//

-- Review ophalen via game_id
-- Author: KB
CREATE PROCEDURE get_reviews_by_game_id(
    IN p_game_id INT
)
BEGIN
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
END //

-- Reviews ophalen via username
-- Author: KB
CREATE PROCEDURE get_reviews_by_user(
    IN p_user_name VARCHAR(150)
)
BEGIN
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
END//

-- Review updaten
-- Author: KB
CREATE PROCEDURE update_review(
    IN p_game_id INT,
    IN p_user_id INT,
    IN p_new_comment VARCHAR(280),
    IN p_new_score TINYINT
)
BEGIN
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
END//

DELIMITER ;
-- get average review score by game ID
-- Author: JD
DELIMITER //

CREATE PROCEDURE get_avg_review(IN p_game_id INT)
BEGIN
    SELECT
        CAST(AVG(`score`) AS DECIMAL(3,1)) AS score
    FROM
        `review`
    WHERE 
        `game_id` = p_game_id;
END //

DELIMITER ;
DELIMITER //

-- Gebruiker toevoegen
-- Author: KB
CREATE PROCEDURE add_user(
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6),
    IN p_email VARCHAR(320),
    IN p_name VARCHAR(150),
    IN p_role VARCHAR(15),
    IN p_password VARCHAR(120)
)
BEGIN
    INSERT INTO user (`postal_code`, `house_number`, `email`, `name`, `role`, `password`)
    VALUES (p_postal_code, p_house_number, p_email, p_name, p_role, p_password);
END //

-- Aanroepen (Uit comment halen)
-- CALL add_user(`1234AB`, `1`, `johndoe@voorbeeld.nl`, `John Doe`, `Admin`, `Breda`);

-- Gebruiker bijwerken
-- Author: JD
CREATE PROCEDURE update_user(
    IN p_user_id INT,
    IN p_postal_code VARCHAR(6),
    IN p_house_number VARCHAR(6),
    IN p_email VARCHAR(320),
    IN p_name VARCHAR(150),
    IN p_role VARCHAR(15),
    IN p_password VARCHAR(120)
)
BEGIN
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
END //

-- Bijvoorbeeld
-- CALL update_user(254, '1010AB', `1B`, null, null); -- om adres te updaten
-- CALL update_user(254, null, null, 'jantje@pietje.nl', null); -- om email te updaten

-- Gebruiker ophalen met ID of email
-- Author: JD
CREATE PROCEDURE get_user(
    IN p_user_id INT,
    IN p_email VARCHAR(320)
)
BEGIN
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
END//

-- Gebruiker verwijderen
-- Author: JD
CREATE PROCEDURE delete_user(
    IN p_user_id INT
)
BEGIN
    DELETE FROM
        `user`
    WHERE
        `user_id` = p_user_id;
END//

DELIMITER ;
DELIMITER //

-- workshop toevoegen
-- Author: JD

CREATE PROCEDURE add_workshop(
    IN p_game_id INT,
    IN p_min_size INT,
    IN p_max_size INT,
    IN p_duration INT,
    IN p_price DECIMAL(10,2)
)
BEGIN
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
END //

-- workshop bijwerken
-- Author: JD
CREATE PROCEDURE update_workshop(
    IN p_game_id INT,
    IN p_min_size INT,
    IN p_max_size INT,
    IN p_duration INT,
    IN p_price DECIMAL(10,2)
)
BEGIN
    UPDATE `workshop`
    SET
        `min_size` = COALESCE(p_min_size, `min_size`),
        `max_size` = COALESCE(p_max_size, `max_size`),
        `duration` = COALESCE(p_duration, `duration`),
        `price` = COALESCE(p_price, `price`)
    WHERE
        `game_id` = p_game_id;
END //

-- workshop verwijderen
-- Author: JD
CREATE PROCEDURE delete_workshop(
    IN p_game_id INT
)
BEGIN
    DELETE FROM `workshop`
    WHERE
        `game_id` = p_game_id;
END //

-- workshop ophalen
-- Author: JD
CREATE PROCEDURE get_workshop(
    IN p_game_id INT
)
BEGIN
    SELECT
        `game_id`,
        `min_size`,
        `max_size`,
        `duration`,
        `price`
    FROM `workshop`
    WHERE
        `game_id` = p_game_id;
END //

DELIMITER ;
DELIMITER //
-- als de prijs in game wordt geüpdatet, 
-- neem de prijswijziging op in audit_prices
-- Author: JD
CREATE TRIGGER audit_game_price_update
AFTER
    UPDATE 
ON 
    `game`
FOR EACH ROW
BEGIN
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
END //

DELIMITER ;
DELIMITER //
-- als een order een update krijgt
-- neem deze mee in audit_order
-- Author: JD

CREATE TRIGGER audit_order_update
AFTER
    UPDATE 
ON 
    `order`
FOR EACH ROW
BEGIN
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
END //

DELIMITER ;
DELIMITER //
-- als de prijs in workshop wordt geüpdatet, 
-- neem de prijswijziging mee in audit_prices
-- Author: JD

CREATE TRIGGER audit_workshop_price_update
AFTER
    UPDATE 
ON 
    `workshop`
FOR EACH ROW
BEGIN
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

END //

DELIMITER ;
