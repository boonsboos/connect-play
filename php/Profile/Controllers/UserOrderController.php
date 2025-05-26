<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Profile/DataAccess/UserRepository.php';
require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Shop/DataAccess/OrderRepository.php';
require_once '/var/www/php/Shop/Domain/Order.php';

class UserOrderController extends Controller
{
    private UserRepository $userRepository;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->orderRepository = new OrderRepository();
    }

    public function getUserOrders(): array
    {
        try {
            // Haal de gebruiker op
            $user = $this->userRepository->getUser($_SESSION["userId"]);
            if (!$user) {
                return throw new Exception("Gebruiker niet gevonden");
            }

            // Haal de bestellingen van de gebruiker op
            $orders = $this->orderRepository->getOrdersByUser($user->getId());
            if (empty($orders)) {
                throw new Exception("Geen bestellingen gevonden voor deze gebruiker");
            }

            foreach ($orders as $order) {
                // Haal de details van elke bestelling op
                $cartEntries = $this->orderRepository->getCartEntriesByOrderId($order->getId());
                $order->setEntries($cartEntries);
            }

            return $orders;
        } catch (Exception $e) {
            // Log de fout of handel deze op een andere manier af
            header("Location: /profiel/bestellingen.php?error=" . urlencode($e->getMessage()));
            die;
        }
    }

    public function getUserOrderById(int $orderId): ?Order
    {
        try {
            // Haal de bestelling op
            $order = $this->orderRepository->getOrderById($orderId);
            if (!$order) {
                throw new Exception("Bestelling niet gevonden");
            }

            // Haal de details van de bestelling op
            $cartEntries = $this->orderRepository->getCartEntriesByOrderId($order->getId());
            $order->setEntries($cartEntries);

            return $order;
        } catch (Exception $e) {
            // Log de fout of handel deze op een andere manier af
            header("Location: /profiel/bestellingen.php?error=" . urlencode($e->getMessage()));
            die;
        }
    }
}
