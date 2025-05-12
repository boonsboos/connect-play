<?php

require_once '/var/www/php/Shop/Domain/CartEntry.php';
require_once '/var/www/php/Shop/Domain/Game.php';

// om PHPUnit te kunnen gebruiken moet eerst de TESTCase worden geïmporteerd
use PHPUnit\Framework\TestCase;

class CartEntryTest extends TestCase {

    public function testAddCopy() {
        // Arrange: Hier maak je het object
        $cartEntry = new CartEntry(); // start met 0 copies

        // Act: Met act wordt de method uitgevoerd
        $cartEntry->addCopy();

        // Assert: controleer of het resultaat juist is
        $this->assertEquals(1, $cartEntry->getCopies());
    }

    public function testRemoveCopy() {
        $cartEntry = new CartEntry(1);

        $cartEntry->removeCopy();

        $this->assertEquals(0, $cartEntry->getCopies());
    }

    public function testToggleWorkshop() {
        $cartEntry = new CartEntry();

        // hier wordt de $workshopEnabled omgezet naar true
        $cartEntry->toggleWorkshop();

        // deze assert verwacht dat isWorkshopEnabled() een true waarde teruggeeft
        $this->assertTrue($cartEntry->isWorkshopEnabled());
    }
}
?>