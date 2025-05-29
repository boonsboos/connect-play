<?php

require_once '/var/www/php/Shared/Database.php';
require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';

class CartRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
}
