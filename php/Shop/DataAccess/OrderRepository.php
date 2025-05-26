<?php
require_once '/var/www/php/Shared/Database.php';
require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';

class OrderRepository
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

    // Deze methode haalt alle bestellingen op voor een specifieke gebruiker
    public function getOrdersByUser(string $userId): array
    {
        $query = "CALL get_orders_by_user(:userId)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':userId' => $userId
        ]);

        $orders = [];
        while ($row = $stmt->fetch()) {
            $orders[] = new Order($row['order_number'], $row['user_id'], $row["date"], OrderStatus::from($row['status']), $row['comment']);
        }

        return $orders;
    }
}
