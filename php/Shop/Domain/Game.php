<?php

namespace Shop\Domain;

class Game {
    
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private int $id,
        private int $players,
        private float $price,
        private int $duration,
        private string $name,
        private string $description,
        private string $difficulty,
        private int $leftInStock
    ) {}

    public function getId(): int {
        return $this->id;
    }

    public function getPlayers(): int {
        return $this->players;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDifficulty(): string {
        return $this->difficulty;
    }

    public function getLeftInStock(): int{
        return $this->leftInStock;
    }

    public function setId(int $gameId) {
        // geeft het id aan het game object nadat deze is opgeslagen in de database
        $this->id = $gameId;
    }

}

?>