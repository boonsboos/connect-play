<?php

require_once 'Address.php';
require_once 'UserRole.php';

class User
{
    private $email;
    private $id;
    private $name;

    private $role = UserRole::CUSTOMER;
    private $addresses = [];

    // zodat de editor het oppakt
    /**
     * @param Address[] $addresses
     */
    public function __construct(string $email, string $id, string $name, UserRole $role, array $addresses)
    {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
        $this->addresses = $addresses;
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
}
