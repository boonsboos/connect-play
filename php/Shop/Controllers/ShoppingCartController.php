<?php
// require_once '/var/www/php/Shared/Debug.php';
if (!isset($_SESSION)) {
    session_start();
}
require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';
require_once '/var/www/php/Shop/DataAccess/OrderRepository.php';
require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';

class ShoppingCartController
{
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function dispatch(): void
    {
        $orderAction = $_POST['action'] ?? null; // deze komt van de orderData.append

        switch ($orderAction) {
            case 'createOrder':
                $this->createOrder();
                break;
            case 'addCartEntries':
                $this->addCartEntries();
                break;
            default:
                // als $_POST['action'] geen waarde heeft 'createOrder' of 'addCartEntries' wordt deze melding gestuurd    
                echo json_encode([
                    'success' => false, 
                    'message' => 'Geen geldige actie meegegeven'
                ]);
                exit; // beter een exit gebruiken zodat php daadwerkelijk stopt
        }
    }

    public function createOrder(): void
    {
        // Zorg dat je een gebruiker hebt
        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Niet ingelogd.']);
            exit;
        }

        $foundOrder = $this->orderRepository->getOrderForUser($userId); // haal de openstaande (PENDING) van de gebruiker op

        if ($foundOrder) {
            $_SESSION["currentOrderNumber"] = $foundOrder->getId();

            echo json_encode([
                'success' => true,
                'orderNumber' => $foundOrder->getId(),
                'userId' => $foundOrder->getUserId()
            ]);
            exit;
        } else{
            try {
                // Maak een nieuwe Order aan (orderNumber is null, wordt door DB gezet)
                $order = new Order(
                    userId: (int)$userId,
                    date: date('Y-m-d'),
                    status: OrderStatus::Pending,
                );

                // Sla op in de database via de repository
                $this->orderRepository->createOrder($order);

                // Zet het ordernummer in de sessie voor het kunenn toevoegen van de entrys
                $_SESSION["currentOrderNumber"] = $order->getId();

                // Zet het ordernummer en userId klaar om in localStorage te stoppen via frontend (optioneel)
                echo json_encode([
                    'success' => true,
                    'orderNumber' => $order->getId(),
                    'userId' => $order->getUserId()
                ]);
                return;
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Fout bij aanmaken van order: ' . $e->getMessage()
                ]);
                return;
            }
        }
    }

    public function addCartEntries(): void
    
    {
        // de waardes in de $_POST komen vanuit de checktout.js het FormData object
        $orderNumber = $_POST['orderNumber'] ?? null;
        $cartEntriesJson = $_POST['cartEntries'] ?? null; // dit is een string, geen array

        // het ordernummer en minimaal één cartentry is nodig om verder te gaan
        if (!$orderNumber || !$cartEntriesJson) {
            echo json_encode(['success' => false, 'message' => 'Geen order of cartentries gevonden.']);
            exit;
        }

        // zet JSON-objecten om naar een associatieve array
        $cartEntries = json_decode($cartEntriesJson, true);

        if (!is_array($cartEntries)) {
            echo json_encode([
                'success' => false,
                'message' => 'Kan cartEntriesJson niet omzetten naar array.'
            ]);
            exit;
        }

        $gameController = new GameController();

        // $allCartEntriesFromOrder = $this->orderRepository->getCartEntriesByOrderId($orderNumber);
        // $vardump = var_dump($allCartEntriesFromOrder);

        // // zet alle gameIds uit localStorage in een lijst
        // $allGameIds = array_map(fn($entry) => (int)$entry["gameId"], $cartEntries);

        // // verwijder alle cartentries uit de database die niet meer in localStorage zitten
        // foreach ($allCartEntriesFromOrder as $cartEntry) {
        //     $game = $cartEntry->getGame();
        //     if (!$game) continue;

        //     if (!in_array($game->getId(), $allGameIds, true)) {
        //         $this->orderRepository->deleteCartEntry($orderNumber, $game->getId());
        //     }
        // }

        foreach ($cartEntries AS $cartEntry) {
            // een game object is nodig voor cartEntry object
            $game = $gameController->getGameById($cartEntry["gameId"]);
            $cartEntry = new CartEntry(
                $orderNumber,
                $game,
                $cartEntry["amount"],
                date('Y-m-d'),
                $cartEntry["price"]
            );

            // controleert of er in de database een match is tussen het ordernummer en het gameId
            if ($this->orderRepository->cartEntryExists($orderNumber, $game->getId())) {
                $this->orderRepository->updateCartEntry($cartEntry);
            } else {
                $this->orderRepository->addCartEntry($cartEntry);
            }
        }

        // na de afhandeling van de entries, success response terug sturen
        echo json_encode([
            'success' => true, 
            'message' => 'De cartEntries zijn toegevoegd aan de database.',
        ]);
        exit;
    }

    public function addCartEntry(int $gameId)
    {
        // Zorg dat je een gebruiker hebt
        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Niet ingelogd.']);
            return;
        }

        $gameController = new GameController();
        $game = $gameController->getGameById($gameId);

        try {
            echo json_encode([
                'success' => true,
                'message' => 'Game toegevoegd aan je winkelwagen.',
                'cartEntry' => [
                    'gameId' => (int) $gameId,
                    'name' => $game->getName(),
                    'price' => $game->getPrice(),
                    'amount' => 1
                ],
                'action' => 'add'
            ]);
            return;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fout bij toevoegen aan winkelwagen: ' . $e->getMessage(),
                'action' => 'add'
            ]);
            return;
        }
    }
}
