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
     * Slaat een nieuwe workshop op
     *
     * @return bool
     * - true als de workshop voldoet en is opgeslagen
     * - false als de workshop niet voldoet of er iets anders misgaat
     */
    public function createWorkshop(): bool
    {
        if (!$this->validateWorkshop()){
            return false;
        }

        try {
            return $this->workshopRepository->createWorkshop(new Workshop(
                (int) $_POST['gameId'],
                (int) $_POST['minplayers'],
                (int) $_POST['maxplayers'],
                (float) $_POST['price'],
                (int) $_POST['duration']
            ));
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Haalt een workshop op op basis van de game ID
     *
     * @param int $gameId
     * @return Workshop|null
     * - Workshop als de game een workshop heeft
     * - null als de game geen workshop heeft
     */
    public function getWorkshop(int $gameId): ?Workshop
    {
        return $this->workshopRepository->getWorkshop($gameId);
    }

    /**
     * Haalt games op die geen workshop hebben geconfigureerd
     *
     * @return Game[]
     */
    public function getGamesWithWorkshops(): array
    {
        return $this->gameRepository->getGamesWithWorkshops();
    }

    /**
     * Werkt een workshop bij
     *
     * @return bool
     * - true als de update geslaagd is
     * - false als de update faalde
     */
    public function updateWorkshop(): bool
    {
        if (!$this->validateWorkshop()){
            return false;
        }

        try {
            return $this->workshopRepository->updateWorkshop(new Workshop(
                (int) $_POST['gameId'],
                (int) $_POST['minplayers'],
                (int) $_POST['maxplayers'],
                (float) $_POST['price'],
                (int) $_POST['duration']
            ));
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Valideert of een nieuwe workshop voldoet
     *
     * @return bool
     * - true als de workshop klopt
     * - false als de workshop niet klopt
     */
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

    /**
     * Verwijdert de workshops voor een game
     *
     * @param int $gameId de game waaraan workshops zijn gekoppeld
     * @return void
     */
    public function removeWorkshop(int $gameId): void
    {
        $this->workshopRepository->removeWorkshop($gameId);
    }

    /**
     * Haalt alle games op zonder workshops
     *
     * @return Game[]
     */
    public function getGamesWithoutWorkshops(): array {
        return $this->gameRepository->getGamesWithoutWorkshops();
    }

    /**
     * Checkt of er een game ID is meegegeven aan de pagina
     *
     * @return bool
     * - true als er een valide game ID is
     * - false als er geen of geen valide game ID is
     */
    public function gameProvided(): bool {
        return isset($_GET['gameId']) && is_numeric($_GET['gameId']);
    }

    /**
     * Haalt een game op op basis van de game ID
     *
     * @param int $gameId de ID van de game
     * @return Game|null
     * - Game als de game bestaat
     * - null als de game niet bestaat
     */
    public function getGame(int $gameId): ?Game {
        return $this->gameRepository->getGame($gameId);
    }
}

?>