<?php

namespace Shop\DataAccess;

class GameRepository {
    
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private PDO $db // zo wordt er herbruikbare pdo object gemaakt die je kunt gebruiken in meerdere methods
    ) {}

    public function addGame(Game $game) {
        // gebruik de prepare() ipv query() om slq injectie voorkomen. Zo kom de invoer niet direcht in de query
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

?>