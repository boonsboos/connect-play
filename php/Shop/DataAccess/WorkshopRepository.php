<?php

require_once '/var/www/php/Shared/Database.php';
require_once '/var/www/php/Shop/Domain/Game.php';
require_once '/var/www/php/Shop/Domain/Workshop.php';

class WorkshopRepository
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
    
    public function createWorkshop(Game $game, Workshop $Workshop): void
    {
        try {
            $stmtWorkshop = $this->db->prepare("CALL add_workshop(:game_id, :min_size, :max_size, :duration, :price)");
            
            $stmtWorkshop->execute([
                ':game_id' => $game->getId(),
                ':min_size' => $Workshop->getMinSize(),
                ':max_size' => $Workshop->getMaxSize(),
                ':duration' => $Workshop->getDuration(),
                ':price' => $Workshop->getPrice(),
            ]);

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new Exception("Workshop voor deze game bestaat al"); 
            }
            throw $e;
        }
    }

    public function getWorkshop(int $gameId): Workshop 
    {
        $stmtWorkshop = $this->db->prepare("CALL get_workshop(:gameId)");

        $stmtWorkshop->execute(['gameId'=> $gameId]);
        
        $workshopRow = $stmtWorkshop->fetch();

        if ($workshopRow != false ) {
            // Retourneert een workshop object met opgehaalde data
            return new Workshop(
                (int) $workshopRow['game_id'],
                (int) $workshopRow['min_size'],
                (int) $workshopRow['max_size'],
                (float) $workshopRow['price'],
                (int) $workshopRow['duration']
            );
        }

        throw new Exception("Geen workshop gevonden voor deze game.");
    }

}

?>