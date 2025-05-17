<?php

class Address
{
<<<<<<< HEAD
    private $postalCode;
    private $houseNumber;
    private $streetName;
    private $city;

    public function __construct(string $postalCode, string $houseNumber, string $streetName, string $city)
    {
        $this->postalCode = $postalCode;
        $this->houseNumber = $houseNumber;
        $this->streetName = $streetName;
        $this->city = $city;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function setStreetName(string $streetName): void
    {
        $this->streetName = $streetName;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }
=======
    public function __construct(private string $postalCode, private int $houseNumber, private string $street, private string $city) {}
>>>>>>> dd8db31ec1b5dab81ce7bceada9ae248599f8628

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

<<<<<<< HEAD
    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getCity(): string
    {
        return $this->city;
=======
    public function getHouseNumber(): int
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(int $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
>>>>>>> dd8db31ec1b5dab81ce7bceada9ae248599f8628
    }
}
