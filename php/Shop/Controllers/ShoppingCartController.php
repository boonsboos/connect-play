<?php

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

    public function dispatch()
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? null;
        $gameId = $_POST['gameId'] ?? null;

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Niet ingelogd.']);
            return;
        }

        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'add':
                $this->addCartEntry($gameId);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Ongeldige actie.']);
        }
    }

    public function create(): void
    {
        // Zorg dat je een gebruiker hebt
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            throw new Exception("Eerst inloggen voordat je iets kan toevoegen aan de winkelwagen.");
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

            // Zet het ordernummer en userId klaar om in localStorage te stoppen via frontend
            echo json_encode([
                'success' => true,
                'orderNumber' => $order->getId(),
                'userId' => $order->getUserId(),
            ]);
            return;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Er is iets misgegaan bij het aanmaken van de bestelling.',
                'action' => 'create'
            ]);
            return;
        }
    }

    public function addCartEntry(int $gameId)
    {

        // Haal het huidige ordernummer uit POST (die frontend moet meesturen)
        $currentOrderJson = $_POST['order'] ?? null;
        $currentOrderData = json_decode($currentOrderJson, true);
        $orderNumber = $currentOrderData['orderNumber'];

        $gameController = new GameController();
        $game = $gameController->getGameById($gameId);

        $cartEntry = new CartEntry(
            orderNumber: $orderNumber,
            game: $game,
            amount: 1,
            when: date('Y-m-d H:i:s'),
        );

        try {
            // Roep de repository aan om een CartEntry toe te voegen
            $this->orderRepository->addCartEntry($cartEntry);

            echo json_encode([
                'success' => true,
                'message' => 'Game toegevoegd aan je winkelwagen.',
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
