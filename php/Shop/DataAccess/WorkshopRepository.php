<?php

require_once '/var/www/php/Shared/Database.php';
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

    /**
     * Slaat een workshop op voor een game
     *
     * @param Workshop $workshop
     * @return void
     */
    public function createWorkshop(Workshop $workshop): bool
    {
        $stmtWorkshop = $this->db->prepare("CALL add_workshop(:game_id, :min_size, :max_size, :duration, :price)");

        return $stmtWorkshop->execute([
            ':game_id' => $workshop->getGameId(),
            ':min_size' => $workshop->getMinSize(),
            ':max_size' => $workshop->getMaxSize(),
            ':duration' => $workshop->getDuration(),
            ':price' => $workshop->getPrice(),
        ]);
    }

    /**
     * Haalt de workshop op van een game
     *
     * @param int $gameId de game om de workshop voor op te halen
     * @return Workshop|null
     * - Workshop als de game een workshop heeft
     * - null als de game geen workshop heeft
     */
    public function getWorkshop(int $gameId): ?Workshop
    {
        $stmtWorkshop = $this->db->prepare("CALL get_workshop(:gameId)");

        $stmtWorkshop->execute(['gameId'=> $gameId]);
        
        $workshopRow = $stmtWorkshop->fetch();

        if (empty($workshopRow)) {
            return null;
        }

        // Retourneert een workshop object met opgehaalde data
        return new Workshop(
            (int)$workshopRow['game_id'],
            (int)$workshopRow['min_size'],
            (int)$workshopRow['max_size'],
            (float)$workshopRow['price'],
            (int)$workshopRow['duration']
        );
    }

    /**
     * Haalt alle workshops op voor een bepaalde game
     * @param int $gameId
     * @return Workshop[]
     * - Leeg als er geen workshops zijn voor de game
     */
    public function getWorkshops(int $gameId): array 
    {
        $workshops = [];

        $stmtWorkshop = $this->db->prepare("SELECT * FROM `workshop` WHERE `game_id` = :gameId");

        $stmtWorkshop->execute(['gameId'=> $gameId]);
        
        $allWorkshops = $stmtWorkshop->fetchAll();

        // als er geen resultaat is, blijft de array leeg
        // omdat de loop niet uitgevoerd wordt
        foreach ($allWorkshops as $workshop) {
            $workshops[] = new Workshop(
                (int) $workshop['game_id'],
                (int) $workshop['min_size'],
                (int) $workshop['max_size'],
                (float) $workshop['price'],
                (int) $workshop['duration']
            );
        }

        return $workshops;
    }

    /**
     * Werk een workshop bij
     *
     * @param Workshop $workshop
     * @return bool
     * - true als het bijwerken lukt
     * - false als het bijwerken faalt
     */
    public function updateWorkshop(Workshop $workshop): bool
    {
        $stmtNewGameInfo = $this->db->prepare("CALL update_workshop(:game_id, :min_size, :max_size, :duration, :price)");

        return $stmtNewGameInfo->execute([
            ':game_id' => $workshop->getGameID(),
            ':min_size' => $workshop->getMinSize(),
            ':max_size' => $workshop->getMaxSize(),
            ':duration' => $workshop->getDuration(),
            ':price' => $workshop->getPrice(),
        ]); 
    }

    /**
     * Verwijdert de workshop van een game
     *
     * @param int $gameId de game om de workshop bij te verwijderen
     * @return void
     */
    public function removeWorkshop(int $gameId): void
    {
        $stmtNewGameInfo = $this->db->prepare("CALL delete_workshop(:game_id)");

        $stmtNewGameInfo->execute([
            ':game_id' => $gameId
        ]);
    }
    
}

?>