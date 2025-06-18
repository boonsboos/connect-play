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

    public function createOrder(Order $order): void
    {
        // Aan de hand van de userId wordt er een Order aangemaakt
        $userId = $order->getUserId();

        try {
            // 1. Order toevoegen
            $stmtOrder  = $this->db->prepare("CALL add_order(:userId)");
            /**
             * In de stored prodecure 'add_order' wordt het volgende al toegevoegd:
             * -----------------------------------
             * -- orderNumber => AUTO_INCREMENT --
             * -- date => CURRENT_DATE()        --
             * -- Status => 'PENDING'           --
             * -----------------------------------
             */
            $stmtOrder->execute([':userId' => $userId]);

            // 2. Haalt het orderNumber op
            $orderId = $stmtOrder->fetchColumn();
            $order->setOrderNumber((int)$orderId);

            // 3. sluit de cursor van de procedure voordat een nieuwe query begint
            $stmtOrder->closeCursor();
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Code 23000 betekent "Integrity constraint violation". je probeert iets toe te voegen dat de db verbied, zoals dubbele orders
                throw new Exception("Ordernummer bestaat al!");  // hier maak je een Exception voor ALLEEN de foutcode 23000 zo worden andere foutmeldingen niet stilgezet
            }
            throw $e; // hier wordt de Exception gegooit voor alle andere fouten
        }
    }

    public function getOrderForUser($userId): ?Order // return type betekend order of een null
    {
        // haal alle orders op die gekoppeld zijn aan de gebruiker
        $allOrders = $this->getOrdersByUser($userId);

        // we willen alleen maar de order die op pending staat terug geven zodat deze afgehandeld kan worden
        foreach ($allOrders as $order) {
            if ($order->getStatus() === OrderStatus::Pending) {
                return $order;
            }
        }

        return null;
    }

    public function addCartEntry(CartEntry $cartEntry): void
    {
        $stmtCartEntry = $this->db->prepare("CALL add_cart_entry(:orderNumber, :gameId, :amount, :when)");

        $stmtCartEntry->execute([
            ':orderNumber' => $cartEntry->getOrderNumber(),
            ':gameId' => $cartEntry->getGame()->getId(), // Zorg dat Game::getId() bestaat
            ':amount' => $cartEntry->getAmount(),
            ':when' => $cartEntry->getWhen(),
        ]);
    }

    public function updateCartEntry(CartEntry $cartEntry): void
    {
        $stmtCartEntry = $this->db->prepare("CALL update_cart_entry(:orderNumber, :gameId, :newAmount, :newWhen)");

        $stmtCartEntry->execute([
            ':orderNumber' => $cartEntry->getOrderNumber(),
            ':gameId' => $cartEntry->getGame()->getId(),
            ':newAmount' => $cartEntry->getAmount(),
            ':newWhen' => $cartEntry->getWhen(),
        ]);
    }

    public function deleteCartEntry(int $orderNumber, int $gameId): void
    {
        $stmtCartEntry = $this->db->prepare("CALL delete_cart_entry(:orderNumber, :gameId)");

        $stmtCartEntry->execute([
            ':orderNumber' => $orderNumber,
            ':gameId' => $gameId
        ]);
    }

    public function cartEntryExists(int $orderNumber, int $gameId): bool // hoeft alleen te checken of ordernummer en gameid overeenkomt
    {
        $stmtCartEntry = $this->db->prepare("CALL get_cart_entry_by_order_and_game(:orderNumber, :gameId)");

        $stmtCartEntry->execute([
            ':orderNumber' => $orderNumber,
            ':gameId' => $gameId
        ]);

        $cartEntryResult = $stmtCartEntry->fetch();

        if ($cartEntryResult) {
            return true;
        }

        return false;
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
            $orders[] = new Order(
                $row['user_id'],
                $row["date"],
                Orderstatus::from($row['status']),
                $row['comment'] ?? '', // omdat de order class een string verwacht gebruik je hier de coalescing operattor ?? (fallback)
                $row['total'] = 0.0,
                $row['entries'] = [],
                $row['order_number']
            );
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
        $stmt->execute([':orderId' => $orderId]);

        $cartEntries = [];
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        foreach ($rows as $row) {
            // haal de game details op
            $query = "CALL get_game(:gameId)";
            $gameStmt = $this->db->prepare($query);
            $gameStmt->execute([':gameId' => $row['game_id']]);

            $game = $gameStmt->fetch();
            if (!$game) {
                continue; // Als de game niet gevonden is, sla deze entry over
            }
            $gameStmt->closeCursor();

            $cartEntries[] = new CartEntry(
                $row['order_number'],
                new Game(
                    (int)$game['players'],
                    (float)$game['price'],
                    (string)$game['duration'],
                    (string)$game['name'],
                    (string)$game['description'],
                    (string)$game['difficulty'],
                    (int)$game['left_in_stock'],
                    (string)$game['image_url'],
                    (int)$game['game_id']
                ),
                (int)$row['amount'],
                (int)$row['when'],
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
                $row['user_id'],
                $row['date'],
                OrderStatus::from($row['status']),
                $row['comment'] ?? '',
                $row['total'] = 0.0,
                $row['entries'] = [],
                $row['order_number']
            );
        }

        return null;
    }
}
