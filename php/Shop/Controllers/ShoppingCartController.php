<?php

require_once '/var/www/php/Shop/Domain/Game.php';

class ShoppingCartController
{
    public function addEntry()
    {
        // Zorg dat je een gebruiker hebt
        $userID = $_SESSION['user_id'] ?? null;
        if (!$userID) {
            throw new Exception("Eerst inloggen voordat je iets kan toevoegen aan de winkelwagen.");
        }
    }
}
