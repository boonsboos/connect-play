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

    public function addGame()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception("Ongeldige methode, alleen POST is toegestaan", 405);
        }
        
        //Controlleren of alle velden zijn ingevuld
        $velden = ['name', 'players', 'price', 'duration', 'description', 'difficulty', 'left_in_stock'];
        foreach ($velden as $veld) {
            if (empty($_POST[$veld])) {
                throw new Exception("Veld '$veld' is verplicht");
            }
        }

        $game = new Game(
            players: (int)$_POST['players'],
            price: (float)$_POST['price'],
            duration: (int)$_POST['duration'],
            name: (string)$_POST['name'],
            description: (string)$_POST['description'],
            difficulty: (string)$_POST['difficulty'],
            leftInStock: (int)$_POST['left_in_stock']
        );

        try {
            $this->gameRepository->addGame($game);
        } catch (Exception $e) {
            throw new Exception("Fout bij het toevoegen van het spel: " . $e->getMessage());
        }

            // Redirect terug naar formulier met succesmelding
        header("Location: /dashboard/addgame.php?success=1");
    exit;
    }
}

?>