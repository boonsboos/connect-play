<?php

require_once '/var/www/php/Shop/Domain/CartEntry.php';
require_once '/var/www/php/Shop/Domain/Game.php';

// om PHPUnit te kunnen gebruiken moet eerst de TESTCase worden geïmporteerd
use PHPUnit\Framework\TestCase;

class CartEntryTest extends TestCase {

    public function testAddAmount()
    {
        // Arrange: Hier maak je het game en cartentry object
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 1", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $cartEntry = new CartEntry("Ordernummer1", $game, 0, "2000-01-01", 50.0, false); // start met 0 copies

        // Act: Met act wordt de method uitgevoerd
        $cartEntry->addAmount();

        // Assert: Controleer of het resultaat juist is
        $this->assertEquals(1, $cartEntry->getAmount());
    }

    public function testRemoveAmount()
    {
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $cartEntry = new CartEntry("Ordernummer1", $game, 1, "2000-01-01", 50.0, false);

        $cartEntry->removeAmount(); // verlaagt het aantal exemplaren (copies)

        $this->assertEquals(0, $cartEntry->getAmount());
    }

    public function testToggleWorkshop()
    {
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 3", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $cartEntry = new CartEntry("Ordernummer1", $game, 1, "2000-01-01", 50.0, false);

        // hier wordt de $workshopEnabled omgezet naar true
        $cartEntry->toggleWorkshop();

        // deze assert verwacht dat isWorkshopEnabled() een true waarde teruggeeft
        $this->assertTrue($cartEntry->isWorkshopEnabled());
    }
    
}

?>