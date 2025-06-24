<?php

require_once '/var/www/php/Profile/DataAccess/UserRepository.php';
require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Shop/DataAccess/OrderRepository.php';
require_once '/var/www/php/Shop/Domain/Order.php';

class UserOrderController
{
    private UserRepository $userRepository;
    private OrderRepository $orderRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->orderRepository = new OrderRepository();
    }

    /**
     * Haalt alle orders op van een gebruiker mits ingelogd
     *
     * @return Order[]
     */
    public function getUserOrders(): array
    {
        // Haal de gebruiker op
        $user = $this->userRepository->getUser($_SESSION["userId"]);
        if (!$user) {
            return [];
        }

        // Haal de bestellingen van de gebruiker op
        $orders = $this->orderRepository->getOrdersByUser($user->getId());
        if (empty($orders)) {
            return [];
        }

        foreach ($orders as $order) {
            // Haal de details van elke bestelling op
            $cartEntries = $this->orderRepository->getCartEntriesByOrderId($order->getId());
            $order->setEntries($cartEntries);
        }

        return $orders;
    }

    /**
     * Haalt de order inclusief artikelen van een gebruiker op op basis van order ID
     *
     * @param int $orderId de id van de order
     * @return Order|null
     * - Order als de order bestaat
     * - null als de order niet bestaat
     */
    public function getUserOrderById(int $orderId): ?Order
    {
        // Haal de bestelling op
        $order = $this->orderRepository->getOrderById($orderId);
        if (!$order) {
            return null;
        }

        // Haal de details van de bestelling op
        $cartEntries = $this->orderRepository->getCartEntriesByOrderId($order->getId());
        $order->setEntries($cartEntries);

        return $order;
    }
}
