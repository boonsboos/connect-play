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
