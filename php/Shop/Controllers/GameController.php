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

    public function getGame()
    {
        // Haalt het ID uit de url, anders staat die op null
        $id = $_GET['id'] ?? null;

        // Check of het id leeg is of niet een nummer is:
        if (!$id || !is_numeric($id)) {
            throw new Exception("Ongeldig of ontbrekend ID");
        }

        $game = $this->gameRepository->getGame($id);

        // Check of de game leeg is
        if (!$game) {
            throw new Exception("Game niet gevonden", 404);
        }

        return $game;
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