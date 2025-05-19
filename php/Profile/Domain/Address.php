<?php

class Address
{
    public function __construct(
        private string $postalCode,
        private string $houseNumber,
        private string $streetName,
        private string $city
    ) {}

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
