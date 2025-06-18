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

    public function addGame(Game $game): void
    {
        try {
            $stmtGame = $this->db->prepare("CALL add_game(:players, :price, :duration, :name, :description, :difficulty, :left_in_stock, :image_url)");

            $stmtGame->execute([
                ':players' => $game->getPlayers(),
                ':price' => $game->getPrice(),
                ':duration' => $game->getDuration(),
                ':name' => $game->getName(),
                ':description' => $game->getDescription(),
                ':difficulty' => $game->getDifficulty(),
                ':left_in_stock' => $game->getLeftInStock(),
                ':image_url' => $game->getImageUrl() // image_url is optioneel, dus kan leeg zijn
            ]);

            $gameId = $stmtGame->fetchColumn(); // haalt 1 waarde op uit het resultaat van de query (dus SELECT LAST_INSERT_ID() AS id)
            $game->setId((int)$gameId);

            $stmtGame->closeCursor();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Code 23000 betekent "Integrity constraint violation". je probeert iets toe te voegen dat de db verbied, zoals dubbele game namen
                throw new Exception("Game naam bestaat al!");  // hier maak je een Exception voor ALLEEN de foutcode 23000 zo worden andere foutmeldingen niet stilgezet
            }
            throw $e; // hier wordt de Exception gegooit voor alle andere fouten
        }
    }

    /**
     * @returns Game[]
     */
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
                (string) $row['image_url'] ?? '', // image_url is optioneel, dus gebruik een lege string als het niet bestaat
                (int) $row['game_id']
            );
        }
        return $allGames;
    }
    
    public function getGame(int $id): ?Game
    {
        $stmtGame = $this->db->prepare("CALL get_game(:id)");

        $stmtGame->execute(['id' => $id]);

        $gameData = $stmtGame->fetch();

        if (!$gameData) {
            throw new Exception("Game niet gevonden.", 404);
        }

        return new Game(
            players: (int)$gameData['players'],
            price: (float)$gameData['price'],
            duration: (int)$gameData['duration'],
            name: $gameData['name'],
            description: $gameData['description'],
            difficulty: $gameData['difficulty'],
            leftInStock: $gameData['left_in_stock'],
            imageUrl: $gameData['image_url'] ?? '',
            id: $gameData['game_id'] // image_url is optioneel, dus gebruik een lege string als het niet bestaat
        );
    }

    public function updateGame(Game $game): void
    {
        // Voer update_game procedure uit
        $stmtNewGameInfo = $this->db->prepare("CALL update_game(:id, :price, :duration, :name, :description, :difficulty, :left_in_stock, :image_url)");

        $stmtNewGameInfo->execute([
            ':id' => $game->getId(),
            ':price' => $game->getPrice(),
            ':duration' => $game->getDuration(),
            ':name' => $game->getName(),
            ':description' => $game->getDescription(),
            ':difficulty' => $game->getDifficulty(),
            ':left_in_stock' => $game->getLeftInStock(),
            ':image_url' => $game->getImageUrl() // image_url is optioneel, dus kan leeg zijn
        ]);
    }

    public function updateGameOptional(array $data): void
    {
        // Voer update_game procedure uit
        $stmt = $this->db->prepare("CALL update_game(:id, :price, :duration, :name, :description, :difficulty, :left_in_stock)");

        $stmt->execute([
            ':id' => $data["game_id"],
            ':price' => $data["price"] ?? null,
            ':duration' => $data["duration"] ?? null,
            ':name' => $data["name"] ?? null,
            ':description' => $data["description"] ?? null,
            ':difficulty' => $data["difficulty"] ?? null,
            ':left_in_stock' => $data["left_in_stock"] ?? null,
            ':image_url' => $data["image_url"] ?? null
        ]);

        $stmt->closeCursor();
    }

    public function removeGame(int $id): void
    {
        $stmtNewGameInfo = $this->db->prepare("CALL delete_game(:id)");

        $stmtNewGameInfo->execute([
            ':id' => $id
        ]);
    }

    /**
     * @return Game[]
     */
    public function getGamesWithoutWorkshops(): array
    {
        $allGames = [];

        $stmtGame = $this->db->prepare("SELECT * FROM `game` WHERE `game_id` NOT IN (SELECT `game_id` FROM `workshop`) ORDER BY `name` ASC;");
        $stmtGame->execute();
        $gameRows = $stmtGame->fetchAll();
        foreach ($gameRows as $row) {
            $allGames[] = new Game(
                (int) $row['players'],
                (float) $row['price'],
                (int) $row['duration'],
                (string) $row['name'],
                (string) $row['description'],
                (string) $row['difficulty'],
                (string) $row['left_in_stock'],
                (string) $row['image_url'] ?? '',
                (int) $row['game_id']
            );
        }

        return $allGames;
    }

    /**
     * @return Game[]
     */
    public function getGamesWithWorkshops(): array
    {
        $allGames = [];

        $stmtGame = $this->db->prepare("SELECT * FROM `game` WHERE `game_id` IN (SELECT `game_id` FROM `workshop`) ORDER BY `name` ASC;");
        $stmtGame->execute();
        $gameRows = $stmtGame->fetchAll();
        foreach ($gameRows as $row) {
            $allGames[] = new Game(
                (int) $row['players'],
                (float) $row['price'],
                (int) $row['duration'],
                (string) $row['name'],
                (string) $row['description'],
                (string) $row['difficulty'],
                (string) $row['left_in_stock'],
                (string) $row['image_url'] ?? '',
                (int) $row['game_id']
            );
        }

        return $allGames;
    }


    public function searchByName(string $name): array
    {
        $stmt = $this->db->prepare("SELECT * FROM game WHERE name LIKE :name");
        $stmt->execute([':name' => '%' . $name . '%']);

        $rows = $stmt->fetchAll();
        $games = [];

        foreach ($rows as $row) {
            $games[] = new Game(
                (int)$row['players'],
                (float)$row['price'],
                (int)$row['duration'],
                (string)$row['name'],
                (string)$row['description'],
                (string)$row['difficulty'],
                (int)$row['left_in_stock'],
                (string)$row['image_url'] ?? '',  // indien van toepassing
                (int)$row['game_id']
            );
        }

        return $games;
    }


}
