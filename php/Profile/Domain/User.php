<?php

require_once 'Address.php';
require_once 'UserRole.php';
require_once './php/Shared/debug.php';

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
        string $id,
        string $name,
        string $password,
        UserRole $role = UserRole::CUSTOMER, // zet default
        array $addresses = [] // niet meegegeven = leeg
    ) {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
        $this->addresses = $addresses;
        $this->password = password_hash($password, PASSWORD_DEFAULT); // encrypt het wachtwoord, met algoritme PASSWORD_DEFAULT
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
        $this->password = password_hash($password, PASSWORD_DEFAULT);
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
