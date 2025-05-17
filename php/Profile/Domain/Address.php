<?php

class Address
{
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

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}
