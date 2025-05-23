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

    public function getGames(): array {
        return $this->gameRepository->getGames();
    }

    public function getGame() {
        return $this->gameRepository->getGame();
    }

    public function addGame(Game $game): void {
        $this->gameRepository->addGame($game);
    }

}

?>