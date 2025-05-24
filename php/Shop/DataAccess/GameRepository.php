<?php

require_once '/var/www/php/Shared/Database.php';
require_once '/var/www/php/Shop/Domain/Game.php';

class GameRepository
{
    private PDO $db;
    public function __construct()
    {
        try {
            $this->db = Database::connect();
        } catch (PDOException $e) { // vang alle fouten op die tijdens de database connectie gebeuren
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    public function addGame(Game $game)
    {
        try {
            $stmtGame = $this->db->prepare("CALL add_game(:players, :price, :duration, :name, :description, :difficulty, :left_in_stock)");

            $stmtGame->execute([
                ':players' => $game->getPlayers(),
                ':price' => $game->getPrice(),
                ':duration' => $game->getDuration(),
                ':name' => $game->getName(),
                ':description' => $game->getDescription(),
                ':difficulty' => $game->getDifficulty(),
                ':left_in_stock' => $game->getLeftInStock()
            ]);

            // haal het id op dat de stored procedure heeft teruggegeven (uit de storerd procedure SELECT LAST_INSERT_ID() AS id;)
            $gameRow = $stmtGame->fetch(); // haalt de eerste rij op na uitvoer van procedure en maak een associatieve array
            if ($gameRow && isset($gameRow['game_id'])) {
                $game->setId((int) $gameRow['game_id']);
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Code 23000 betekent "Integrity constraint violation". je probeert iets toe te voegen dat de db verbied, zoals dubbele game namen
                throw new Exception("Game naam bestaat al!");  // hier maak je een Exception voor ALLEEN de foutcode 23000 zo worden andere foutmeldingen niet stilgezet
            }
            throw $e; // hier wordt de Exception gegooit voor alle andere fouten
        }
    }

    public function getGames(): array
    {
        $allGames = [];

        $stmtGame = $this->db->prepare("SELECT * FROM `game`");

        $stmtGame->execute();

        $gameRows = $stmtGame->fetchAll();

        foreach ($gameRows as $row) {
            // PDO retourneert standaard alle kolommen als string, dit kan typefouten veroorzaken.
            // Daarom wordt de waarde hier duidelijk teruggezet naar het oorspronkelijke type.
            $allGames[] = new Game(
                (int) $row['players'],
                (float) $row['price'],
                (int) $row['duration'],
                (string) $row['name'],
                (string) $row['description'],
                (string) $row['difficulty'],
                (int) $row['left_in_stock'],
                (int) $row['game_id']
            );
        }
        return $allGames;
    }

    public function getGame(int $id)
    {
        $stmt = $this->db->prepare("CALL get_game(:id)");

        $stmt->execute(['id' => $id]);

        $gameData = $stmt->fetch();

        if (!$gameData) {
            return null;
        }

        return new Game(
            players: $gameData['players'],
            price: $gameData['price'],
            duration: $gameData['duration'],
            name: $gameData['name'],
            description: $gameData['description'],
            difficulty: $gameData['difficulty'],
            leftInStock: $gameData['left_in_stock'],
        );
    }
}
