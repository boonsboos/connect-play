<?php

class Game
{
    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private int $players,
        private float $price,
        private int $duration,
        private string $name,
        private string $description,
        private string $difficulty,
        private int $leftInStock,
        // optioneel, wordt pas gebruikt na aanmaken game
        // een optionele variabele zet je altijd als laatste (sinds php 8.0)
        private ?int $id = null 
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayers(): int
    {
        return $this->players;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    public function getLeftInStock(): int
    {
        return $this->leftInStock;
    }

    public function setId(int $gameId): void
    {
        // geeft het id aan het game object nadat deze is opgeslagen in de database
        $this->id = $gameId;
    }

        public function setPlayers(int $players): void
    {
        $this->players = $players;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function setLeftInStock(int $leftInStock): void
    {
        $this->leftInStock = $leftInStock;
    }

}

?>