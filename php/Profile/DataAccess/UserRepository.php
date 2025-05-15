<?php

require_once '../php/Profile/Domain/User.php';
require_once '../php/Shared/Database.php';

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
     * @throws Exception
     */
    public function getUser($emailOrId): User
    {
        if ($emailOrId) {
            $sql = $this->db->prepare("SELECT * FROM user WHERE email = :email OR user_id = :id;");
            $sql->execute([
                ':email' => $emailOrId,
                ':id' => (int)$emailOrId,
            ]);
        }
        $user = $sql->fetch();
        if (!$user) {
            throw new Exception("Gebruiker niet gevonden."); // gooit een error als de gebruiker niet gevonden is
        }

        return new User(
            $user['email'],
            $user['user_id'],
            $user['name'],
            $user['password'],
            UserRole::from($user['role']),
        );
    }

    public function updateUser(User $user): void
    {
        $sql = $this->db->prepare("CALL update_user(:id, :postal_code, :house_number, :email, :role, :pass);");
        $sql->execute([
            ':id' => $user->getId(),
            ':postal_code' => null,
            ':house_number' => null,
            ':email' => $user->getEmail(),
            ':role' => $user->getRole()->value,
            ':pass' => $user->getPassword(),
        ]);
    }
}
