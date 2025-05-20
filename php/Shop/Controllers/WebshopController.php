<?php

require_once '../php/Shop/DataAccess/WebshopRepository.php';

class WebshopController {

    private WebshopRepository $webshopRepository;

    /**
     * Aantal spellen per pagina
     */
    private int $gamesPerPage = 6;

    public function __construct() {
        // Aanmaken van een instantie van de WebshopRepository class om databaseoperaties uit te voeren
        $this->webshopRepository = new WebshopRepository();
    }

    /**
     * Ophalen van de huidige pagina uit de URL-querystring (met een standaardwaarde van 1 als 'page' niet is ingesteld)
     * @return int de huidige pagina
     */
    public function getCurrentPage(): int {
        return isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }

    public function getGames(int $amountOfGames = 0): array {
        // Als er geen limiet wordt meegegeven, houd de standaard limiet aan van 6
        if ($amountOfGames <= 0) {
            $amountOfGames = $this->gamesPerPage;
        } else /* if ($limit > 0) */ {
            $this->gamesPerPage = $amountOfGames;
        }

        // Haal op op welke pagina we zitten
        $currentPage = $this->getCurrentPage();

        // Geef de juiste hoeveelheid games terug van de juiste pagina
        return $this->webshopRepository->getAllGames($currentPage, $amountOfGames);
    }

    public function getTotalOfGames(): int {
        return $this->webshopRepository->getTotalGames();
    }

    public function getTotalPages(): int {
        return ceil($this->getTotalOfGames() / $this->gamesPerPage);
    }
}
?>