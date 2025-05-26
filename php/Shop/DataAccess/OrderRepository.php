<?php
require_once '/var/www/php/Shared/Database.php';
require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';

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

    /**
     * Deze methode haalt alle bestellingen op voor een specifieke gebruiker
     *
     * @param string $userId
     * @return Order[]
     */
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

    /**
     * Deze methode haalt de details van een specifieke bestelling op
     * 
     * @param string $orderId
     * @return CartEntry[]
     */
    public function getCartEntriesByOrderId(string $orderId): array
    {
        $query = "CALL get_cart_entries_by_order(:orderId)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':orderId' => $orderId
        ]);

        $cartEntries = [];
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $stmt->closeCursor(); // Sluit de cursor om de volgende query te kunnen uitvoeren

            // haal de game details op
            $query = "CALL get_game(:gameId)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':gameId' => $row['game_id']
            ]);

            $game = $stmt->fetch();
            if (!$game) {
                continue; // Als de game niet gevonden is, sla deze entry over
            }

            $cartEntries[] = new CartEntry(
                $row['order_number'],
                new Game(
                    $game['players'],
                    $game['price'],
                    $game['duration'],
                    $game['name'],
                    $game['description'],
                    $game['difficulty'],
                    $game['left_in_stock'],
                    (int)$game['game_id']
                ),
                (int)$row['amount'],
                $row['when'],
                (float)$row['price_snapshot']
            );
        }

        return $cartEntries;
    }

    public function getOrderById(string $orderId): ?Order
    {
        $query = "CALL get_order(:orderId)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':orderId' => $orderId
        ]);

        if ($row = $stmt->fetch()) {
            return new Order(
                $row['order_number'],
                $row['user_id'],
                $row["date"],
                OrderStatus::from($row['status']),
                $row['comment']
            );
        }

        return null;
    }
}
