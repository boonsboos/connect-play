<?php

require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Shared/Database.php';

class UserRepository
{
    private PDO $db;
    public function __construct()
    {
        try {
            // Maak een nieuwe databaseverbinding
            $this->db = Database::connect();
        } catch (PDOException $e) {
            // Log de foutmelding of geef een foutmelding weer
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    public function addUser(User $user)
    {
        // Gebruik de prepare() ipv query() om sql injectie voorkomen. Zo komt de invoer niet direct in de query
        $address = $user->getAddresses()[0];

        // 1. Kijkt of address bestaat
        $addressStmt = $this->db->prepare("CALL get_address(:postal_code, :house_number);");
        $addressStmt->execute([
            ':postal_code' => $address->getPostalCode(),
            ':house_number' => $address->getHouseNumber(),
        ]);

        // 2. Als address nog niet bestaat voeg toe
        if (!$addressStmt->fetch()) {
            $StmtAddress = $this->db->prepare("CALL add_address(:postal_code, :house_number, :street_name, :city)");
            $StmtAddress->execute([
                ':postal_code' => $address->getPostalCode(),
                ':house_number' => $address->getHouseNumber(),
                ':street_name' => $address->getStreetName(),
                ':city' => $address->getCity()
            ]);
        } else {
            $addressStmt = $this->db->prepare("CALL update_address(:postal_code, :house_number, :street_name, :city);");
            $addressStmt->execute([
                ':postal_code' => $address->getPostalCode(),
                ':house_number' => $address->getHouseNumber(),
                ':street_name' => $address->getStreetName(),
                ':city' => $address->getCity(),
            ]);
        }

        // 3. Voeg gebruiker toe
        $stmtUser = $this->db->prepare("CALL add_user(:postal_code, :house_number, :email, :name, :role, :password)");
        $stmtUser->execute([
            ':postal_code' => $address->getPostalCode(),
            ':house_number' => $address->getHouseNumber(),
            ':email' => $user->getEmail(),
            ':name' => $user->getName(),
            ':role' => $user->getRole()->value,
            ':password' => $user->getPassword(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function getUser($emailOrId): User
    {
        if (!$emailOrId) {
            throw new Exception("Email of Id niet meegegeven");
        }

        $sql = $this->db->prepare("CALL get_user(:id, :email);");
        $sql->execute([
            ':id' => (int)$emailOrId,
            ':email' => $emailOrId,
        ]);
        $user = $sql->fetch();
        if (!$user) {
            throw new Exception("Gebruiker niet gevonden."); // gooit een error als de gebruiker niet gevonden is
        }

        $sql = $this->db->prepare("CALL get_address(:postal_code, :house_number);");
        $sql->execute([
            ':postal_code' => $user['postal_code'],
            ':house_number' => $user['house_number'],
        ]);

        $address = $sql->fetch(); // haal het adres op

        return new User(
            $user['user_id'],
            $user['email'],
            $user['name'],
            $user['password'],
            UserRole::from($user['role']),
            $address ? [
                new Address(
                    $address['postal_code'] ?? '',
                    $address['house_number'] ?? '',
                    $address['street_name'] ?? '',
                    $address['city'] ?? '',
                )
            ] : []
        );
    }

    public function updateUser(User $user): void
    {
        $addressStmt = $this->db->prepare("CALL get_address(:postal_code, :house_number);");
        $addressStmt->execute([
            ':postal_code' => $user->getAddresses()[0]->getPostalCode(),
            ':house_number' => $user->getAddresses()[0]->getHouseNumber(),
        ]);
        if (!$addressStmt->fetch()) {
            $addressStmt = $this->db->prepare("CALL add_address(:postal_code, :house_number, :street_name, :city);");
            $addressStmt->execute([
                ':postal_code' => $user->getAddresses()[0]->getPostalCode(),
                ':house_number' => $user->getAddresses()[0]->getHouseNumber(),
                ':street_name' => $user->getAddresses()[0]->getStreetName(),
                ':city' => $user->getAddresses()[0]->getCity(),
            ]);
        } else {
            $addressStmt = $this->db->prepare("CALL update_address(:postal_code, :house_number, :street_name, :city);");
            $addressStmt->execute([
                ':postal_code' => $user->getAddresses()[0]->getPostalCode(),
                ':house_number' => $user->getAddresses()[0]->getHouseNumber(),
                ':street_name' => $user->getAddresses()[0]->getStreetName(),
                ':city' => $user->getAddresses()[0]->getCity(),
            ]);
        }
        $userStmt = $this->db->prepare("CALL update_user(:id, :postal_code, :house_number, :email, :name, :role, :pass);");
        $userStmt->execute([
            ':id' => $user->getId(),
            ':postal_code' => $user->getAddresses()[0]->getPostalCode(),
            ':house_number' => $user->getAddresses()[0]->getHouseNumber(),
            ':email' => $user->getEmail(),
            ':name' => $user->getName(),
            ':role' => $user->getRole()->value,
            ':pass' => $user->getPassword(),
        ]);
    }
}
