<?php
require_once '../../Shared/Database.php';
require_once '../Domain/Game.php';

class GameRepository
{
    private PDO $db;
    public function __construct()
    {
        try {
            $this->db = Database::connect();
        } catch (PDOException $e) {
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    public function addGame(Game $game)
    {
        // gebruik de prepare() ipv query() om sql injectie te voorkomen. Zo komt de invoer niet direct in de query
        $stmt = $this->db->prepare("CALL add_game(:price, :players, :duration, :name, :description, :difficulty, :leftInStock)");

        $stmt->execute([
            ':price' => $game->getPrice(),
            ':players' => $game->getPlayers(),
            ':duration' => $game->getDuration(),
            ':name' => $game->getName(),
            ':description' => $game->getDescription(),
            ':difficulty' => $game->getDifficulty(),
            ':leftInStock' => $game->getLeftInStock()
        ]);

        // haal het id op dat de stored procedure heeft teruggegeven (uit de storerd procedure SELECT LAST_INSERT_ID() AS id;)
        $gameRow = $stmt->fetch(); // haalt de eerste rij op na uitvoer van procedure en maak een associatieve array
        if ($gameRow && isset($gameRow['id'])) {
            $game->setId((int) $gameRow['id']);
        }
    }
}
