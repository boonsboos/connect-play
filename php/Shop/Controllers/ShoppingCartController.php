<?php
require_once '/var/www/php/Shared/Debug.php';
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

    public function dispatch()
    {
        header('Content-Type: application/json');

        $action = $_POST['action'] ?? null;
        $gameId = $_POST['gameId'] ?? null;

        if (!isset($_SESSION['userId'])) {
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
        $userId = $_SESSION['userId'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Niet ingelogd.']);
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

            // Zet het ordernummer en userId klaar om in localStorage te stoppen via frontend
            echo json_encode([
                'success' => true,
                'orderNumber' => $order->getId(),
                'userId' => $order->getUserId()
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
        $orderNumber = $_POST['orderNumber'] ?? null;
        if (!$orderNumber) {
            echo json_encode([
                'success' => false,
                'message' => 'Ordernummer ontbreekt',
                'action' => 'add'
            ]);
            return;
        }

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
            //$this->orderRepository->addCartEntry($cartEntry);

            echo json_encode([
                'success' => true,
                'message' => 'Game toegevoegd aan je winkelwagen.',
                'cartEntry' => [
                    'gameId' => $gameId,
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
