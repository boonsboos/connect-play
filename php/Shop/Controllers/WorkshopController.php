<?php

require_once '/var/www/php/Shop/DataAccess/WorkshopRepository.php';
require_once '/var/www/php/Shop/Domain/Workshop.php';
require_once "/var/www/php/Shop/DataAccess/GameRepository.php";
require_once "/var/www/php/Shop/Domain/Game.php";

class WorkshopController
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
        if (!$this->validateWorkshop()){
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

    public function getWorkshop(int $gameId): ?Workshop
    {
        return $this->workshopRepository->getWorkshop($gameId);
    }

    public function getGamesWithWorkshops(): array
    {
        return $this->gameRepository->getGamesWithWorkshops();
    }

    public function updateWorkshop(): bool
    {
        if (!$this->validateWorkshop()){
            return false;
        }

        return $this->workshopRepository->updateWorkshop(new Workshop(
            (int) $_POST['gameId'],
            (int) $_POST['minplayers'],
            (int) $_POST['maxplayers'],
            (float) $_POST['price'],
            (int) $_POST['duration']
        ));
    }

    private function validateWorkshop(): bool {
        // valideer dat alle data beschikbaar is en het juiste formaat heeft
        if (!isset($_POST['gameId']) || !is_numeric($_POST['gameId'])
            || !isset($_POST['minplayers']) || !is_numeric($_POST['minplayers'])
            || !isset($_POST['maxplayers']) || !is_numeric($_POST['maxplayers'])
            || !isset($_POST["price"]) || !preg_match("/\d+(([.,])\d{2})?/", $_POST["price"])
            || !isset($_POST["duration"]) || !is_numeric($_POST['duration']))
        {
            return false;
        }

        // check of de game al een workshop heeft
        if (!is_null($this->getWorkshop($_POST["gameId"]))) {
            return false;
        }

        // minimum aantal spelers moet groter zijn dan 0
        if ($_POST["minplayers"] > $_POST["maxplayers"]) {
            return false;
        }

        // prijs moet boven de 0 zijn
        if ($_POST["price"] <= 0) {
            return false;
        }

        // duration moet minstens 30 minutesn zijn en deelbaar door 30
        if ($_POST["duration"] < 30 || $_POST["duration"] % 30 != 0) {
            return false;
        }

        return true;
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