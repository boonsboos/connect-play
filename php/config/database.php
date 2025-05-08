<?php
$servername = "mariadb";
$username = "root";
$password = "";
$name = "webshop";

$db = null;
try {
    $db = new PDO("mysql:host=$servername;dbname=$name", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // zet de error mode naar exception
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // zet de fetch mode naar associative array
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
