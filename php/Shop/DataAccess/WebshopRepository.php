<?php

// Inclusie van noodzakelijke bestanden
// 'Database.php' bevat de connectie met de database.
// 'Game.php' definieert de Game-klasse die we later gebruiken om game-objecten aan te maken.
require_once '../php/Shared/Database.php';
require_once '../php/Shop/Domain/Game.php';

class webshopRepository {
    private PDO $db;

    // Constructor die de databaseverbinding initialiseert
    public function __construct() {
        try {
            $this->db = Database::connect();
        } catch (PDOException $e) {
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    // Functie om alle spellen op te halen met paginering
    public function getAllGames(int $page = 1, int $limit = 6): array {

        // Validatie van de pagina- en limietparameters
        // Berekening van de offset voor paginering
        // De OFFSET bepaalt vanaf welk record de query resultaten moet ophalen.
        // Bijvoorbeeld:
        // - Bij pagina 1: OFFSET = (1 - 1) * 6 = 0 (eerste 6 resultaten)
        // - Bij pagina 2: OFFSET = (2 - 1) * 6 = 6 (volgende 6 resultaten)
        // - Bij pagina 3: OFFSET = (3 - 1) * 6 = 12 (volgende 6 resultaten)
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT game_id, `name`, price, players, duration, `description`, difficulty, left_In_Stock FROM game LIMIT :limit OFFSET :offset");

        // Bind de parameters voor de limiet en offset
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        // maakt een array van Game-objecten aan
        // De fetch methode haalt alle resultaten op als een array van objecten.
        $games = [];
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            // Maak een nieuw Game-object voor elke rij in de resultaten.
            $game = new Game(
            (int) $row->game_id,
            (int) $row->players,
            (float) $row->price,
            (int) $row->duration,
            (string) $row->name,
            (string) $row->description,
            (string) $row->difficulty,
            (int) $row->left_In_Stock
            );
            // Voeg het Game-object toe aan de array van spellen.
            $games[] = $game;
        }
        return $games;
    }

    // Functie om het totale aantal spellen in de database op te halen
    // Dit is belangrijk voor paginering, zodat we weten hoeveel pagina's er zijn.
    public function getTotalGames(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM game");
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return (int) $result->total;
    }
}
?>
