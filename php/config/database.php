<?php
// Deze functie maakt een verbinding met de database en retourneert een PDO-object.
function getConnection(): PDO|null
{
    $servername = "mariadb"; // de naam van de server. dit is niet localhost omdat de server in een docker container draait
    $username = "root";
    $password = "";
    $name = "webshop";

    try {
        $db = new PDO("mysql:host=$servername;dbname=$name", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // zet de error mode naar exception
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // zet de fetch mode naar associative array
        return $db;
    } catch (PDOException $e) {
        return null;
    }
};
