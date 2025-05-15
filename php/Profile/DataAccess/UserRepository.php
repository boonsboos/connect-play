<?php

require_once '../php/Profile/Domain/User.php';
require_once '../php/config/database.php';

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getConnection();
        if ($this->db === null) {
            throw new Exception("Database connection failed.");
        }
    }

    public function addUser(User $user)
    {
        // gebruik de prepare() ipv query() om slq injectie voorkomen. Zo kom de invoer niet direcht in de query

        $stmt = $this->db->prepare("CALL add_user(:postal_code, :house_number, :email, :name, :role, :password)");
        $stmt = $this->db->prepare("CALL add_address(:postal_code, :house_number, :street_name, :city");
        $stmt->execute([
            ':postal_code' => $user->getAddresses("postal_code"),
            ':house_number' => $user->getAddresses("house_number"),
            ':email' => $user->getEmail(),
            ':name' => $user->getName(),
            ':role' => $user->getRole(),
            ':password' => $user->getRole(),
        ]);
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
