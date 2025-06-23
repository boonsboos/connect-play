<?php

require_once '/var/www/php/Shared/Database.php';

class WebshopRepository
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

    public function saveEmptySearch($searchTerm, $userId, $ipAddress)
    {
        $stmtSr = $this->db->prepare("CALL add_no_search_result(:search_term, :user_id, :ip_address)");
        
        $stmtSr->execute([
            ':search_term' => $searchTerm,
            ':user_id' => $userId,
            ':ip_address' => $ipAddress
        ]);

    }

    public function getEmptySearchResults(): array
    {
        $stmtSr = $this->db->prepare("CALL get_no_search_result()");

        $stmtSr->execute();

        return $stmtSr->fetchAll();
    }

}

?>