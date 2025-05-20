<?php

require_once '../php/Shop/DataAccess/WebshopRepository.php';

// Aanmaken van een instantie van de WebshopRepository class om databaseoperaties uit te voeren
$controller = new webshopRepository();

try {
    // Ophalen van de huidige pagina uit de URL-querystring (met een standaardwaarde van 1 als 'page' niet is ingesteld)
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Aantal spellen per pagina instellen
    $limit = 6;

    // Ophalen van de spellenlijst voor de huidige pagina met de gedefinieerde limiet
    $games = $controller->getAllGames($currentPage, $limit);

    // Ophalen van het totale aantal spellen om paginering te berekenen
    $totalGames = $controller->getTotalGames();

    // Totale aantal pagina's berekenen op basis van het totale aantal spellen en de limiet
    // De functie ceil() rondt een getal altijd naar boven af naar het dichtstbijzijnde gehele getal
    $totalPages = ceil($totalGames / $limit);
} catch (Exception $e) {
    // Handle the exception (log it, display an error message, etc.)
    echo 'Error: ' . $e->getMessage();
}
?>