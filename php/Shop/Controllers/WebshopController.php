<?php

require_once '/var/www/php/Shop/DataAccess/GameRepository.php';
require_once '/var/www/php/Shop/DataAccess/WebshopRepository.php';

class WebshopController {

    private GameRepository $gameRepository;
    private WebshopRepository $webshopRepository;

    private string $searchQuery;
    private int $maxPlayers = 16;
    private int $minPlayers = 2;

    private bool $filterActive = false;

    private array $games = [];
    private array $emptySearchResults = [];

    /**
     * Aantal spellen per pagina
     */
    private int $gamesPerPage = 6;

    public function __construct() 
    {
        // Aanmaken van een instantie van de GameRepository en WebshopRepository class om databaseoperaties uit te voeren
        $this->gameRepository = new GameRepository();
        $this->webshopRepository = new WebshopRepository();
    }

    /**
     * Ophalen van de huidige pagina uit de URL-querystring (met een standaardwaarde van 1 als 'page' niet is ingesteld)
     * @return int de huidige pagina
     */
    public function getCurrentPage(): int {
        return isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }

    /**
     * @return Game[]
     */
    public function getGames(): array
    {
        // We moeten 1 aftrekken van de huidige pagina zodat de offset goed staat
        // (1 - 1) = 0 * 6 = de eerste 6 items
        // (2 - 1) = 1 * 6 = de tweede 6 items
        $offset = ($this->getCurrentPage() - 1) * $this->gamesPerPage;

        // Haal de slice op
        return array_slice(
            $this->games,
            $offset,
            $this->gamesPerPage
        );
    }

    public function fetchGames(int $amountOfGames = 0): void {
        // Als er een custom limiet wordt meegegeven, houd die aan, gebruik anders de standaard
        if ($amountOfGames > 0) {
            $this->gamesPerPage = $amountOfGames;
        } else {
            $this->gamesPerPage = 6;
        }

        // Sla de games voor deze pagina op
        $this->games = $this->gameRepository->getGames();

        // Sla de gefilterde games op
        $this->games = $this->filterGames($this->games);

        if ($this->filterActive && count($this->games) == 0) { // $this->games is een array en je telt hier de opgeslagen waarden
            $webshopRepository = New WebshopRepository;
            $userId = isset($_SESSION["userId"]) ? $_SESSION["userId"] : null; // als er een session is waarbij die geset, is dat wordt die toegevoegd anders is de waar de null
            $webshopRepository->saveEmptySearch($this->searchQuery, $userId, $_SERVER['REMOTE_ADDR']); // Zoekresultaten, userId en Ip-address wordt naar de repo verzonden
        }
    }

    public function getTotalOfGames(): int {
        return count($this->games);
    }

    /** Rondt naar boven af hoeveel pagina's aan games er zijn
     * @return int het aantal pagina's
     */
    public function getTotalPages(): int {
        return ceil($this->getTotalOfGames() / $this->gamesPerPage);
    }

    public function setFilterSettings(array $filterSettings): void
    {
        $this->filterActive = true;
        $this->searchQuery = $filterSettings['search'] ?? '';
        $this->maxPlayers = $filterSettings['maxspelers'];
        $this->minPlayers = $filterSettings['minspelers'];
    }

    /**
     * Filtert de games op basis van de ingestelde filters
     * @param Game[] $games
     * @return Game[]
     */
    private function filterGames(array $games): array
    {
        // als de filter niet actief is, sla filteren over
        if (!$this->filterActive) {
            return $games;
        }

        // filter op min spelers
        $games = array_filter($games, function($game) {
            return $game->getPlayers() >= $this->minPlayers;
        });

        // filter op max spelers
        $games = array_filter($games, function($game) {
            return $game->getPlayers() <= $this->maxPlayers;
        });

        // filter op zoekopdracht
        $games = array_filter($games, function($game) {
            // allebei in lowercase zodat hoofdletters niet uitmaken
            return str_contains(
                strtolower($game->getName()),
                strtolower($this->searchQuery)
            ) || str_contains(
                strtolower($game->getDescription()),
                strtolower($this->searchQuery)
            );
        });

        return $games;
    }
    
    public function getFilterParams(): string
    {
        if ($this->filterActive) {
            return "&search=$this->searchQuery&maxspelers=$this->maxPlayers&minspelers=$this->minPlayers";
        }

        return "";
    }

    public function getEmptySearchResults()
    {
         return $this->webshopRepository->getEmptySearchResults();
    }

}

?>