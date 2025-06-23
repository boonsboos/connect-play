<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once '/var/www/php/Shop/DataAccess/GameRepository.php';
require_once '/var/www/php/Shop/DataAccess/OrderRepository.php';
require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';
require_once '/var/www/php/Profile/DataAccess/UserRepository.php';

class ShoppingCartController
{
    private OrderRepository $orderRepository;
    private GameRepository $gameRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->gameRepository = new GameRepository();
        $this->userRepository = new UserRepository();
    }

    public function dispatch(): void
    {
        $orderAction = $_POST['action'] ?? null; // deze komt van de orderData.append

        switch ($orderAction) {
            case 'createOrder':
                $this->createOrder();
                break;
            case 'addCartEntries':
                $this->saveCartEntries();
                break;
            default:
                // als $_POST['action'] geen waarde heeft 'createOrder' of 'addCartEntries' wordt deze melding gestuurd    
                echo json_encode([
                    'success' => false, 
                    'message' => 'Geen geldige actie meegegeven'
                ]);
        }

        // zorg ervoor dat niks verder kan lopen dan de voltooide dispatch
        exit;
    }

    /**
     * De getCheckoutOrder wordt gebruikt om een array met de benodigde informatie te sturen naar de checkoutpagina
     *
     * @return array
     */
    public function getCheckoutOrder(): array
    {
        $userId = $_SESSION['userId'] ?? null;
        
        // controller of gebruiker is ingelogd anders return een false message
        if (!$userId) {
            return ['success' => false, 'message' => 'Niet ingelogd.'];
        }

        // Haal de order op die gekoppeld is aan de user uit de database
        $order = $this->orderRepository->getLatestPendingOrderForUser($userId);

        // Controleer of er ene order aanwezig is
        if (!$order) {
            return ['success' => false, 'message' => 'Geen bestelling gevonden.'];
        }

        // Indien order aanwezig haal alle cartEntries op die gekoppeld zijn aan de order
        $cartEntries = $this->orderRepository->getCartEntriesByOrderId($order->getId());

        $user = $this->userRepository->getUser((int)$userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User bestaat niet (meer)',
                'orderId' => -1,
                'username' => 'Onbekend',
                'address' => null,
                'cartEntries' => []
            ];
        }

        $username = $user->getName();
        $address = $user->getAddresses()[0];

        return [
            'success' => true,
            'orderId' => $order->getId(),
            'username' => $username,
            'address' => $address,
            'cartEntries' => $cartEntries
        ];
    }
    
    public function createOrder(): void
    {
        $userId = $this->validateUser();

        $foundOrder = $this->orderRepository->getLatestPendingOrderForUser($userId); // Haal de openstaande (PENDING) van de gebruiker op

        if ($foundOrder) {
            $_SESSION["currentOrderNumber"] = $foundOrder->getId();

            echo json_encode([
                'success' => true,
                'orderNumber' => $foundOrder->getId(),
                'userId' => $foundOrder->getUserId()
            ]);
            return;
        }

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
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Fout bij aanmaken van order: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Sla de cart entries op in de database bij de order
     * @return void
     */
    public function saveCartEntries(): void
    {
        // de waardes in de $_POST komen vanuit de checkout.js, het FormData object
        $orderNumber = $_POST['orderNumber'] ?? null;
        $cartEntriesJson = $_POST['cartEntries'] ?? null;

          // het ordernummer en minimaal één cartentry is nodig om verder te gaan
        if (!$orderNumber || !$cartEntriesJson) {
            echo json_encode([
                'success' => false,
                'message' => 'Geen order of cartentries gevonden.'
            ]);
            return;
        }

        // zet JSON string om naar een associatieve array
        $cartEntries = json_decode($cartEntriesJson, true);

        // controleer of $cartEntries een array is
        if (!is_array($cartEntries)) {
            echo json_encode([
                'success' => false,
                'message' => 'Kan cartEntriesJson niet omzetten naar array.'
            ]);
            return;
        }

        // haal alle bestaande cartEntries op uit de database adhv ordernummer
        $allCartEntriesFromOrder = $this->orderRepository->getCartEntriesByOrderId($orderNumber);

        // haal de gameids uit de database en localstorage
        $allCartEntriesGameIds = array_map(fn($entry) => $entry->getGame()->getId(), $allCartEntriesFromOrder);
        $gameIdsFromlocalStorage = array_map(fn($entry) => (int)$entry['gameId'], $cartEntries);

        // verwijder entries in de database die niet meer aanwezig zijn in localStorage
        $gameIdsToDelete = array_diff($allCartEntriesGameIds, $gameIdsFromlocalStorage);
        foreach ($gameIdsToDelete as $gameId) {
            $this->orderRepository->deleteCartEntry($orderNumber, $gameId);
        }

        // voeg cartEntry toe of werk deze bij in de database
        foreach ($cartEntries as $entry) {
            // een game object is nodig voor cartEntry object
            $game = $this->gameRepository->getGame($entry["gameId"]);

            $cartEntry = new CartEntry(
                $orderNumber,
                $game,
                $entry["amount"],
                date('Y-m-d'),
                $entry["price"]
            );

            if ($this->orderRepository->cartEntryExists($orderNumber, $game->getId())) {
                $this->orderRepository->updateCartEntry($cartEntry);
            } else {
                $this->orderRepository->addCartEntry($cartEntry);
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Winkelwagen opgeslagen'
        ]);
        exit;
    }

    public function addCartEntry(int $gameId): void
    {
        $this->validateUser();

        $game = $this->gameRepository->getGame($gameId);

        if (!$game) {
            echo json_encode([
                'success' => false,
                'message' => 'Game niet gevonden',
                'action' => 'add'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Game toegevoegd aan je winkelwagen.',
            'cartEntry' => [
                'gameId' => $gameId,
                'name' => $game->getName(),
                'price' => $game->getPrice(),
                'amount' => 1
            ],
            'action' => 'add'
        ]);
    }

    /**
     * Deze functie callt exit/die als de user niet is ingelogd
     * en retourneert de ID als dat wel zo is.
     *
     * @return int de id van de user
     */
    private function validateUser(): int {
        // Zorg dat je een gebruiker hebt
        $userId = $_SESSION['userId'] ?? null;

        if (empty($userId) || !is_numeric($userId)) {
            echo json_encode(['success' => false, 'message' => 'Niet ingelogd.']);
            exit;
        }

        return (int) $userId;
    }
}
