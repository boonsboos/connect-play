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
        // Haalt het ID uit de url, anders staat die op null
        $id = $_GET['id'] ?? null;
        
        // Check of het id leeg is of niet een nummer is:
        if(!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing ID']);
            return;
        }

        $game = $this->gameRepository->getGame($id);

        // Check of de game leeg is
        if(!game) {
            http_response_code(404);
            echo json_encode(['error' => 'Game not found']);
            return;
        }

        return $game;
    }

    public function addGame(Game $game): void {
        $this->gameRepository->addGame($game);
    }

}

?>