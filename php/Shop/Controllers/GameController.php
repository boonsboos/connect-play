<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Shop/DataAccess/GameRepository.php';
require_once '/var/www/php/Shop/Domain/Game.php';

class GameController extends Controller
{
    private GameRepository $gameRepository;

    public function __construct()
    {
        $this->gameRepository = new GameRepository();
    }

    public function getGames(): array
    {
        return $this->gameRepository->getGames();
    }

    public function getGame(): Game
    {
        // Haalt het ID uit de url, anders staat die op null
        $id = $_GET['id'] ?? null;

        // Check of het id leeg is of niet een nummer is:
        if (!$id || !is_numeric($id)) {
            throw new Exception("Ongeldig of ontbrekend ID");
        }

        return $this->getGameById((int)$id); //de (int) forceert dat $id een integer wordt
    }

    // met de methode getGameById heb je de mogelijheid om een game adhv een id op te halen
    public function getGameById(int $id): Game
    {
        return $this->gameRepository->getGame($id);
    }

    public function getGameByName(string $name): Game
    {
        return $this->gameRepository->getGameByName($name);
    }

    public function addGame(Game $game): void
    {
        $this->gameRepository->addGame($game);
    }
    

    public function updateGame(Game $game): void
    {
        // Haal eerst de game op als deze bestaad
        // Indien de game niet aanwezig is gooit de gameRepository een Exception
        $this->gameRepository->getGame($game->getId());

        // Voer update op game uit
        $this->gameRepository->updateGame($game);
    }

    public function removeGame(int $gameId): void
    {
        $this->gameRepository->getGame($gameId);

        $this->gameRepository->removeGame($gameId);
    }
    
}

?>