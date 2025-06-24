<?php

require_once '/var/www/php/Shop/DataAccess/GameRepository.php';
require_once '/var/www/php/Shop/Domain/Game.php';

class GameController
{
    private GameRepository $gameRepository;

    public function __construct()
    {
        $this->gameRepository = new GameRepository();
    }

    /**
     * Haalt alle games op
     *
     * @return Game[]
     */
    public function getGames(): array
    {
        return $this->gameRepository->getGames();
    }

    /**
     * Haalt de game op aan de hand game ID die meegegeven wordt in de query parameters
     *
     * @return Game|null
     * - Game als de game bestaat
     * - null als de game niet bestaat
     */
    public function getGame(): ?Game
    {
        // Haalt het ID uit de url, anders staat die op nul
        $id = $_GET['id'] ?? 0;

        return $this->gameRepository->getGame((int) $id); // cast naar int om zeker te zijn van type
    }

    /**
     * Verwijdert een game aan de hand van de game ID
     *
     * @param int $gameId de id van de game die verwijderd moet worden
     * @return void
     */
    public function removeGame(int $gameId): void
    {
        $this->gameRepository->getGame($gameId);

        $this->gameRepository->removeGame($gameId);
    }

    /**
     * Voegt een nieuwe game toe
     *
     * @return void
     * @throws Exception als validatie faalt
     */
    public function addGame(): void
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

        $imageUrl = $_POST['image_url'] ?? '';
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
            imageUrl: (string)$_POST['image_url'],// Image URL is optioneel en wordt hier niet gebruikt
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

        try {
            $this->gameRepository->addGame($game);
        } catch (Exception $e) {
            throw new Exception("Fout bij het toevoegen van het spel: " . $e->getMessage());
        }

            // Redirect terug naar formulier met succesmelding
        header("Location: /dashboard/addgame.php?success=1");
        exit;
    }

    /**
     * Zoekt games bij naam
     *
     * @param string $name
     * @return Game[]
     */
    public function searchGamesByName(string $name): array
    {
        return $this->gameRepository->searchByName($name);
    }

    /**
     * Werkt een game bij
     *
     * @return void
     * @throws Exception als validatie faalt
     */
    public function updateGame(): void
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
            imageUrl: (string)$_POST['image_url'], // Image URL is optioneel en wordt hier niet gebruikt
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