<?php

namespace Shop\DataAccess;

class GameRepository {
    
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private PDO $db // zo wordt er herbruikbare pdo object gemaakt die je kunt gebruiken in meerdere methods
    ) {}

    public function addGame(Game $game) {
        
        $stmt = $this->db->prepare("INSERT INTO `game`
                                (player, price, duration, name, description, difficulty, left_in_stock)
                                VALUES
                                (:player, :price, :duration, :name, :description, :difficulty, :leftInStock)"
                            );

        $stmt->execute([
            ':player' => $game->getPlayers(),
            ':price' => $game->getPrice(),
            ':duration' => $game->getDuration(),
            ':name' => $game->getName(),
            ':description' => $game->getDescription(),
            ':difficulty' => $game->getDifficulty(),
            ':leftInStock' => $game->getLeftInStock()
        ]);

        // pdo heeft een functie lastInsertId die geeft dus het id terug van de record waarin de gegeven worden opgeslagen
        // het opgehaalde id wordt toegevoegd aan het game object
        $game->setId((int) $this->db->lastInsertId());
    }

    public function getGames() {
        
    }
}

?>