<?php

require_once '../php/Profile/Domain/User.php';
require_once '../php/config/database.php';

class UserRepository
{
    private PDO $db;
    private array $users;

    public function __construct()
    {
        $this->db = getConnection();
        if ($this->db === null) {
            throw new Exception("Database connection failed.");
        }
        // $this->users = [
        //     new User(
        //         "jd@connect-en-play.nl",
        //         "JD1ID",
        //         "John Doe",
        //         "JD1",
        //         UserRole::EMPLOYEE,
        //         [new Address("London", "SW1A 2AA", "Downing Street 10")]
        //     ),
        //     new User(
        //         "admin@connect-en-play.nl",
        //         "AD1ID",
        //         "Admin",
        //         "AD1",
        //         UserRole::ADMINISTRATOR,
        //         [new Address("Amsterdam", "2234 AD", "Kalverstraat 3")]
        //     ),
        //     new User(
        //         "cardmaster@cardcore.nl",
        //         "COID",
        //         "Fred Langmaker",
        //         "Welkom01",
        //         UserRole::ADMINISTRATOR,
        //         [new Address("Goes", "6969 FU", "Opwerkstraaat 69")]
        //     ),
        //     new User(
        //         "connectenplayfan1998@fastmail.com",
        //         "CO1ID",
        //         "Connie Enp",
        //         "Welkom02",
        //         UserRole::ADMINISTRATOR,
        //         [new Address("Berlijn", "10176", "Friedrichshain 3")]
        //     )
        // ];
    }

    /**
     * @throws Exception
     */
    public function getUser(string $emailOrId): User
    {
        $sql = $this->db->prepare("SELECT * FROM user WHERE user_id = :id OR email = :id");
        $sql->execute([
            ':id' => $emailOrId,
        ]);

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
}
