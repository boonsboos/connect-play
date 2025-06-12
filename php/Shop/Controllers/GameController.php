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

        $imageUrl = $_POST['image_url']?? '';
        if (!empty($imageUrl)) {
            if (
                // Controleren of imgurl een url is en een geldige extensie heeft
                !filter_var($imageUrl, FILTER_VALIDATE_URL) ||
                !preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl)
            ) {
                throw new Exception("Ongeldige afbeeldings-URL");
            }
        }

        $game = new Game(
            players: (int)$_POST['players'],
            price: (float)$_POST['price'],
            duration: (int)$_POST['duration'],
            name: (string)$_POST['name'],
            description: (string)$_POST['description'],
            difficulty: (string)$_POST['difficulty'],
            leftInStock: (int)$_POST['left_in_stock'],
            imageUrl: (string)$_POST['image_url'] ?? '',// Image URL is optioneel en wordt hier niet gebruikt
            id: (int)$_POST['id']
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

    public function searchGamesByName(string $name): array
    {
        return $this->gameRepository->searchByName($name);
    }

    public function updateGame()
    {
        // Controleer of de request een POST is
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception("Ongeldige methode, alleen POST is toegestaan", 405);
        }

        // Controleren of verplichtte velden zijn ingevuld
        $velden = ['id', 'name', 'players', 'price', 'duration', 'description', 'difficulty', 'left_in_stock'];
        foreach ($velden as $veld) {
            if (!isset($_POST[$veld]) || $_POST[$veld] === '') {
                throw new Exception("Veld '$veld' is verplicht");
            }
        }
        
        // Controleren of imgurl gevuld is
        $imageUrl = $_POST['image_url']?? '';
        if (!empty($imageUrl)) {
            if (
                // Controleren of imgurl een url is en een geldige extensie heeft
                !filter_var($imageUrl, FILTER_VALIDATE_URL) ||
                !preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $imageUrl)
            ) {
                throw new Exception("Ongeldige afbeeldings-URL");
            }
        }

        // Aanmaken van een Game object met de gegevens uit het formulier
        // We gebruiken de id uit het formulier om de game te updaten
        $game = new Game(
            players: (int)$_POST['players'],
            price: (float)$_POST['price'],
            duration: (int)$_POST['duration'],
            name: (string)$_POST['name'],
            description: (string)$_POST['description'],
            difficulty: (string)$_POST['difficulty'],
            leftInStock: (int)$_POST['left_in_stock'],
            imageUrl: (string)$_POST['image_url'] ?? '', // Image URL is optioneel en wordt hier niet gebruikt
            id: (int)$_POST['id']
        );

        // Controleren of spelers tussen 1 en 100 liggen
        if ($game->getPlayers() < 1 || $game->getPlayers() > 100) {
            throw new Exception("Aantal spelers moet tussen 1 en 100 liggen.");
        }
        
        // Controleren of de prijs een positief nummer is
        if ($game->getPrice() < 0) {
            throw new Exception("Prijs mag niet negatief zijn.");
        }
    
        // Controleren of de duur tussen 1 minuut en 24 uur ligt
        if ($game->getDuration() < 1 || $game->getDuration() > 1440) {
            throw new Exception("Duur moet tussen 1 minuut en 24 uur liggen.");
        }

        // Controleren of voorraad positief nummer is
        if ($game->getLeftInStock() < 0) {
            throw new Exception("Voorraad mag niet negatief zijn.");
        }

        // Updaten van de game in de database
        $this->gameRepository->updateGame($game);
        // Redirect terug naar formulier met succesmelding
        header("Location: /dashboard/editgame.php?id=" . $game->getId() . "&success=1");
        exit;
    }
}