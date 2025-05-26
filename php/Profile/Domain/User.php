<?php

require_once 'Address.php';
require_once 'UserRole.php';

class User
{
    // zodat de editor het oppakt
    /**
     * @param Address[] $addresses
     */
    public function __construct(
        private string $id,
        private string $email,
        private string $name,
        private string $password,
        private UserRole $role = UserRole::CUSTOMER, // zet default
        private array $addresses = [] // niet meegegeven = leeg
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    /**
     * @return Address[]
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
    }

    /**
     * @param Address[] $addresses
     */
    public function setAddresses(array $addresses): void
    {
        $this->addresses = $addresses;
    }

    public function addAddress(Address $address): void
    {
        $this->addresses[] = $address;
    }
}
