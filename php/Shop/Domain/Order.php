<?php require_once '/var/www/php/Shop/Domain/CartEntry.php';

class Order
{
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private int $id,
        private int $userId,
        private string $date,
        private OrderStatus $status,
        private ?string $comment = '',
        private float $total = 0.0,
        /** @var CartEntry[] */
        private array $entries = []
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): void
    {
        $this->total = $total;
    }

    // voeg een CartEntry object toe aan de entries lijst
    public function addEntry(CartEntry $entry): void
    {
        // wordt toegevoegd aan het einde van de array
        $this->entries[] = $entry;
        // herbereken de totale prijs van de order
        $this->total = $this->calculateTotal();
    }

    /**
     * @return CartEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    // zet de entries array naar de nieuwe array
    public function setEntries(array $entries): void
    {
        $this->entries = $entries;
        // herbereken de totale prijs van de order
        $this->total = $this->calculateTotal();
    }

    public function removeEntry(int $entryNumber)
    {
        // controleer of het index nummer voorkomt in de array
        if (array_key_exists($entryNumber, $this->entries)) {
            // verwijder het item uit de array
            unset($this->entries[$entryNumber]);
            // om te voorkomen dat er gatenkaas ontstaat moet de array geherindext worden
            $this->entries = array_values($this->entries);
        }
    }

    private function calculateTotal(): float
    {
        $total = 0.0;
        foreach ($this->entries as $entry) {
            $total += $entry->getPriceSnapshot() * $entry->getCopies();
        }
        return $total;
    }
}
