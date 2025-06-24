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

    /**
     * Slaat het account van de nieuwe gebruiker op
     *
     * @param User $user de gebruiker die een account aanmaakt
     * @return void
     * @throws PDOException als iets faalt aan de databasekant
     */
    public function addUser(User $user): void
    {
        $address = $user->getAddresses()[0];

        // 1. Kijkt of address bestaat
        $addressStmt = $this->db->prepare("CALL get_address(:postal_code, :house_number);");
        $addressStmt->execute([
            ':postal_code' => $address->getPostalCode(),
            ':house_number' => $address->getHouseNumber(),
        ]);

        $existingAddress = $addressStmt->fetch();
        $addressStmt->closeCursor();
        // 2. Als address nog niet bestaat voeg toe
        if (!$existingAddress) {
            $StmtAddress = $this->db->prepare("CALL add_address(:postal_code, :house_number, :street_name, :city)");
            $StmtAddress->execute([
                ':postal_code' => $address->getPostalCode(),
                ':house_number' => $address->getHouseNumber(),
                ':street_name' => $address->getStreetName(),
                ':city' => $address->getCity()
            ]);
            $StmtAddress->closeCursor();
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
        $stmtUser->closeCursor();
    }

    /**
     * Haalt een gebruiker op aan de hand van hun e-mailadres of user ID
     *
     * @param string $emailOrId het e-mailadres of de user ID
     * @return User|null
     * - User als de gebruiker een account heeft
     * - null als de gebruiker geen account heeft
     */
    public function getUser(string $emailOrId): ?User
    {
        if (!$emailOrId) {
            return null;
        }

        $sql = $this->db->prepare("CALL get_user(:id, :email);");
        $sql->execute([
            ':id' => (int)$emailOrId,
            ':email' => $emailOrId,
        ]);
        $user = $sql->fetch();
        if (!$user) {
            return null; // gooit een error als de gebruiker niet gevonden is
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

    /**
     * Werkt de gegevens van een gebruiker bij
     *
     * @param User $user
     * @return bool
     * - true als het gelukt is
     * - false als het niet gelukt is
     */
    public function updateUser(User $user): bool
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
        return $userStmt->execute([
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
