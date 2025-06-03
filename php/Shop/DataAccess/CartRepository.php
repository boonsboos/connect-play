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

    public function addCartEntry(CartEntry $cartEntry): void
    {
        try {
            $stmtGame = $this->db->prepare("CALL add_cart_entry(:order_number, :game_id , :amount, :when)");
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Code 23000 betekent "Integrity constraint violation". je probeert iets toe te voegen dat de db verbied, zoals dubbele game namen
                throw new Exception("Game naam bestaat al!");  // hier maak je een Exception voor ALLEEN de foutcode 23000 zo worden andere foutmeldingen niet stilgezet
            }
            throw $e; // hier wordt de Exception gegooit voor alle andere fouten
        }
    }
}
