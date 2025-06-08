<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Shop/DataAccess/WorkshopRepository.php';
require_once '/var/www/php/Shop/Domain/Workshop.php';
require_once "/var/www/php/Shop/DataAccess/GameRepository.php";
require_once "/var/www/php/Shop/Domain/Game.php";

class WorkshopController extends Controller
{
    private WorkshopRepository $workshopRepository;
    private GameRepository $gameRepository;

    public function __construct() {
        $this->workshopRepository = new WorkshopRepository();
        $this->gameRepository = new GameRepository();
    }

    public function createWorkshop(Game $game, Workshop $workshop): void
    {
        $this->workshopRepository->createWorkshop($game, $workshop);
    }

    public function getWorkshop(int $gameId): Workshop
    {
        return $this->workshopRepository->getWorkshop($gameId);
    }

    public function getWorkshops(int $gameId): array
    {
        return $this->workshopRepository->getWorkshops($gameId);
    }

    public function updateWorkshop(Workshop $workshop): void
    {
        // Controleer of er een workshop bestaat voor deze game
        // Workshop niet aanwezig dan gooit de workshopRepository een Exception
        $this->workshopRepository->getWorkshop($workshop->getGameId());
        
        // Voeg update op workshop uit
        $this->workshopRepository->updateWorkshop($workshop);
    }

    public function removeWorkshop(int $gameId): void
    {
        $this->workshopRepository->getWorkshop($gameId);

        $this->workshopRepository->removeWorkshop($gameId);
    }

    /**
     * @return Game[]
     */
    public function getGamesWithoutWorkshops(): array {
        return $this->gameRepository->getGamesWithoutWorkshops();
    }

    public function gameProvided(): bool {
        return isset($_GET['gameId']) && is_numeric($_GET['gameId']);
    }

    public function getGame(int $gameId): Game {
        return $this->gameRepository->getGame($gameId);
    }
}

?>