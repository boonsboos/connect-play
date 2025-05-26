<?php
require_once '/var/www/php/Shop/Domain/Game.php';
class CartEntry
{
    public function __construct(
        private string $orderNumber,
        private ?Game $game,
        private int $copies = 0,
        private string $when = '',
        private float $priceSnapshot = 0.0,
        private bool $workshopEnabled = false
    ) {}

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getWhen(): string
    {
        return $this->when;
    }

    public function getPriceSnapshot(): float
    {
        return $this->priceSnapshot;
    }

    public function addCopy()
    {
        $this->copies++;
    }

    public function removeCopy()
    {
        if ($this->copies > 0) {
            $this->copies--;
        }
    }

    public function getCopies(): int
    {
        return $this->copies;
    }

    public function toggleWorkshop(): void
    {
        // zodra de geburiker op de toggle knop druk zal de booleean status veranderen
        $this->workshopEnabled = !$this->workshopEnabled;
    }

    public function isWorkshopEnabled(): bool
    {
        // geeft de boolean terug van de workshop
        return $this->workshopEnabled;
    }
}
