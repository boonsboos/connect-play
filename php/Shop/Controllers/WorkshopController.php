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

    /**
     * @throws Exception als er iets fout gaat met de database
     */
    public function createWorkshop(): bool
    {
        // valideer formulier
        if (!isset($_POST['gameId']) || !is_numeric($_POST['gameId'])
            || !isset($_POST['minplayers']) || !is_numeric($_POST['minplayers'])
            || !isset($_POST['maxplayers']) || !is_numeric($_POST['maxplayers'])
            || !isset($_POST["price"]) || !preg_match("/\d+(([.,])\d{2})?/", $_POST["price"])
            || !isset($_POST["duration"]) || !is_numeric($_POST['duration']))
        {
            var_dump($_POST);
            return false;
        }

        // check of de game al een workshop heeft
        try {
            $this->getWorkshop($_POST["gameId"]);
            return false;
        } catch (Exception) {
            // continue, exception betekent dat er geen workshop is
        }

        // minimum aantal spelers moet groter zijn dan 0
        if ($_POST["minplayers"] > $_POST["maxplayers"]) {
            return false;
        }

        // prijs moet boven de 0 zijn
        if ($_POST["price"] <= 0) {
            return false;
        }

        // duration moet groter zijn dan 0
        if ($_POST["duration"] <= 0) {
            return false;
        }

        $this->workshopRepository->createWorkshop(new Workshop(
            (int) $_POST['gameId'],
            (int) $_POST['minplayers'],
            (int) $_POST['maxplayers'],
            (float) $_POST['price'],
            (int) $_POST['duration']
        ));
        return true;
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