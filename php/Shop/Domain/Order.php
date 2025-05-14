<?php

namespace Shop\Domain;

class Order {

    private array $entries;

    function __construct() {
        $this->entries = [];
    }
    
    // voeg een CartEntry object toe aan de entries lijst
    public function addEntry(CartEntry $entry): void {
        // wordt toegevoegd aan het einde van de array
        $this->entries[] = $entry;
    }

    public function getEntries() {
        return $this->entries;
    }
    
    public function removeEntries(int $entryNumber) {
        // controleer of het index nummer voorkomt in de array
        if (array_key_exists($entryNumber, $this->entries)) {
            // verwijder het item uit de array
            unset($this->entries[$entryNumber]);
        }
    }

}