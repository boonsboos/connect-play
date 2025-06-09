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
            $stmt = $this->db->prepare("CALL add_order(:userId)");
            /**
             * In de stored prodecure 'add_order' wordt het volgende al toegevoegd:
             * -----------------------------------
             * -- orderNumber => AUTO_INCREMENT --
             * -- date => CURRENT_DATE()        --
             * -- Status => 'PENDING'           --
             * -----------------------------------
             */
            $stmt->execute([':userId' => $userId]);
            $stmt->closeCursor(); // sluit de cursor van de procedure voordat een nieuwe query begint

            // 2. Haalt het orderNumber op
            $result = $this->db->query("SELECT LAST_INSERT_ID() AS order_number");
            $row = $result->fetch();

            if ($row && isset($row['order_number'])) {
                $orderNumber = (int)$row['order_number'];
                $order->setOrderNumber($orderNumber);
            } else {
                throw new Exception("Order is aangemaakt maar het ordernummer kon niet worden opgehaald.");
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Code 23000 betekent "Integrity constraint violation". je probeert iets toe te voegen dat de db verbied, zoals dubbele game namen
                throw new Exception("Game naam bestaat al!");  // hier maak je een Exception voor ALLEEN de foutcode 23000 zo worden andere foutmeldingen niet stilgezet
            }
            throw $e; // hier wordt de Exception gegooit voor alle andere fouten
        }
    }

    /**
     * WORDT NOG NIET GEBRUIKT!!!
     */
    public function addCartEntry(CartEntry $cartEntry): void
    {
        // Verbind met database
        $stmt = $this->db->prepare("CALL add_cart_entry(:orderNumber, :gameId, :amount, :when)");

        // Stored procedure voegt de CartEntry toe aan de database
        $stmt->execute([
            ':orderNumber' => $cartEntry->getOrderNumber(),
            ':gameId' => $cartEntry->getGame()->getId(), // Zorg dat Game::getId() bestaat
            ':amount' => $cartEntry->getAmount(),
            ':when' => $cartEntry->getWhen(),
        ]);
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
                $row['comment'],
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
                    $game['game_id'],
                    $game['players'],
                    (float)$game['price'],
                    $game['duration'],
                    $game['name'],
                    $game['description'],
                    $game['difficulty'],
                    $game['left_in_stock']
                ),
                $row['amount'],
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
                $row['user_id'],
                $row['date'],
                OrderStatus::from($row['status']),
                $row['comment'],
                $row['total'] = 0.0,
                $row['entries'] = [],
                $row['order_number']
            );
        }

        return null;
    }
}
