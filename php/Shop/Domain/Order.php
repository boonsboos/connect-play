<?php

namespace Shop\Domain;

class Order {

    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private array $entries = []
    ) {}
    
    // voeg een CartEntry object toe aan de entries lijst
    public function addEntry(CartEntry $entry): void {
        // wordt toegevoegd aan het einde van de array
        $this->entries[] = $entry;
    }

    public function getEntries(): array {
        return $this->entries;
    }
    
    public function removeEntry(int $entryNumber) {
        // controleer of het index nummer voorkomt in de array
        if (array_key_exists($entryNumber, $this->entries)) {
            // verwijder het item uit de array
            unset($this->entries[$entryNumber]);
            // om te voorkomen dat er gatenkaas onstaat moet de array geherindext worden
            $this->entries = array_values($this->entries);
        }
    }

}

?>