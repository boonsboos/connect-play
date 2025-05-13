<?php

require_once '/var/www/php/Shop/Domain/Order.php';
require_once '/var/www/php/Shop/Domain/CartEntry.php';

// om PHPUnit te kunnen gebruiken moet eerst de TESTCase worden geïmporteerd
use PHPUnit\Framework\TestCase;
use Shop\Domain\Order;
use Shop\Domain\CartEntry;

class OrderTest extends TestCase {

    public function testAddEntry() {
        // Arrange: Hier maak je het object
        $order = New Order();
        $cartEntry = New CartEntry();

        // Act: Met act wordt de method uitgevoerd
        $order->addEntry($cartEntry);

        // Assert: Controleer of het resultaat juist is
        $this->assertCount(1, $order->getEntries());
    }

    public function testRemoveEntries() {
        $order = New Order();
        $entry1 = New CartEntry();
        $entry2 = New CartEntry();

        $order->addEntry($entry1);
        $order->addEntry($entry2);

        $order->removeEntries(1);

        $entries = $order->getEntries();
        
        // controleer of er één entry over is
        $this->assertCount(1, $entries);

        // controleer of entry inderdaad $entry1 is
        $this->assertSame($entry1, $entries[0]);
    }
}
?>