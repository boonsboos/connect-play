<?php

class Workshop {
    
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private int $gameID,
        private int $minSize,
        private int $maxSize,
        private float $price,
        private int $duration
    ) {}

    public function getGameId(): int
    {
        return $this->gameID;
    }

    public function getMinSize(): int
    {
        return $this->minSize;
    }

    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setMinSize(int $minSize): void
    {
        $this->minSize = $minSize;
    }

    public function setMaxSize(int $maxSize): void
    {
       $this->maxSize = $maxSize;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

}

?>