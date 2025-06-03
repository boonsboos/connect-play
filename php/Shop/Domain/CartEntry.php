<?php

require_once '/var/www/php/Shop/Domain/Game.php';

class CartEntry
{
    public function __construct(
        private string $orderNumber,
        private Game $game,
        private int $amount = 1,
        private string $when = "",
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

    public function addAmount()
    {
        $this->amount++;
    }

    public function removeAmount()
    {
        if ($this->amount > 0) {
            $this->amount--;
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
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
