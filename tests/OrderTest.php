<?php

require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';
require_once '/var/www/php/Shop/Domain/OrderStatus.php';

// om PHPUnit te kunnen gebruiken moet eerst de TESTCase worden geïmporteerd
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase {

    public function testCreateOrder() 
    {
        // Arrange: Hier maak je het game, cartentry en order object
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 1", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $cartEntry = new CartEntry("Ordernummer1", $game, 1, "2000-01-01", 50.0, false);
        $order = new Order(1, "2000-01-01", OrderStatus::Pending, "Nieuwe bestelling", 0.0, []);

        // Act: Met act wordt de method uitgevoerd
        $order->addEntry($cartEntry);

        // Assert: Controleer of het resultaat juist is
        $this->assertCount(1, $order->getEntries());
    }
  
    public function testRemoveOrder()
    {
        // Arrange:
        $game = new Game(8, 25.5, 120, "Dark Masters of Dark 1", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $cartEntry = new CartEntry("Ordernummer1", $game, 1, "2000-01-01", 50.0, false);
        $order = new Order(1, "2000-01-01", OrderStatus::Pending, "Nieuwe bestelling 2", 0.0, []);

        // Act:
        $order->addEntry($cartEntry);
        $order->removeEntry(0);

        // Assert: Controleer of er geen entries meer zijn
        $this->assertCount(0, $order->getEntries());
        }

  }

?>