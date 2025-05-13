<?php 

namespace Shop\Domain;

class CartEntry {
    private int $copies;
    private bool $workshopEnabled = false;

    public function __construct(int $copies = 0) // hiermee is de default waarde van $copies op 0 gezet
    {
     $this->copies = $copies;   
    }
    
    public function addCopy (){
        $this->copies++;
    }

    public function removeCopy() {
        if ($this->copies > 0) {
            $this->copies--;
        }
    }
    
    public function getCopies(){
        return $this->copies;
    }

    public function toggleWorkshop(): void {
        // zodra de geburiker op de toggle knop druk zal de booleean status veranderen
        $this->workshopEnabled = !$this->workshopEnabled;
    }

    public function isWorkshopEnabled(): bool {
        // geeft de boolean terug van de workshop
        return $this->workshopEnabled;
    }
}