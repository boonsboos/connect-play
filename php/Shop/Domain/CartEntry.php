<?php 

class CartEntry {

    private bool $workshopEnabled = false;
  
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private int $copies = 0 // hiermee is de default waarde van $copies op 0 gezet
    ) {}
  
    public function addCopy (){
        $this->copies++;
    }

    public function removeCopy() {
        if ($this->copies > 0) {
            $this->copies--;
        }
    }
    
    public function getCopies(): int {
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

?>