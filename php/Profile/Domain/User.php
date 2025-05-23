<?php

require_once 'Address.php';
require_once 'UserRole.php';

class User
{
    private $email;
    private $id;
    private $name;

    private string $password;
    private $role;
    private $addresses;

    // zodat de editor het oppakt
    /**
     * @param Address[] $addresses
     */
    public function __construct(
        string $email,
        string $name,
        string $password,
        UserRole $role = UserRole::CUSTOMER, // zet default
        array $addresses = [] // niet meegegeven = leeg
    ) {
        $this->email = $email;
        $this->name = $name;
        $this->role = $role;
        $this->addresses = $addresses;
        $this->password = $password;
    }

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
