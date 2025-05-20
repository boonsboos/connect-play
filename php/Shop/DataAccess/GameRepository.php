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
        } catch (PDOException $e) {
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    public function addGame(Game $game)
    {
        try { 
            // gebruik de prepare() ipv query() om sql injectie te voorkomen. Zo komt de invoer niet direct in de query
            $stmtGame = $this->db->prepare("SELECT `game`.`name` FROM `game`");

            $stmtGame->execute();

            // geeft een array van alle opgehaalde namen van games
            $gameNames = $stmtGame->fetchAll();

            // loop door alle game namen heen om te controleren of de naam al bestaat
            foreach ($gameNames AS $gameName) {
                if ($gameName['name'] === $game->getName()) {
                    // bestaat de naam al gooi dna een Exception
                    throw new Exception("Game naam bestaat al!");
                }
            }
            
            // gebruik de prepare() ipv query() om sql injectie te voorkomen. Zo komt de invoer niet direct in de query
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

        } catch (Exception $e) {
            // laat het error bericht zien
            echo $e->getMessage();
            die();
        }
    }

    public function getGames(): array  {
        $allGames = [];

        // gebruik de prepare() ipv query() om sql injectie te voorkomen. Zo komt de invoer niet direct in de query
        $stmtGame = $this->db->prepare("SELECT * FROM `game`");

        $stmtGame->execute();

        // geeft een array van alle opgehaalde rijen (games)
        $gameRows = $stmtGame->fetchAll();

        // loop door alle rijen heen en maakt per rij een Game object aan en stopt deze gegevens in een array
        foreach ($gameRows as $row) {
            // Soms zet PDO opgehaalde waarden om naar strings. Dit kan typefouten veroorzaken.
            // Daarom wordt de waarde hier duidelijk teruggezet naar het oorspronkelijke type.
            $allGames[] = new Game(
                (int) $row['players'],
                (float) $row['price'],
                (int) $row['duration'],
                $row['name'],
                $row['description'],
                $row['difficulty'],
                (int) $row['left_in_stock'],
                (int) $row['game_id']
            );
        }
        return $allGames;
    }

}

?>