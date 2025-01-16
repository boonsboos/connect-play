<?php

require_once './php/Profile/Domain/User.php';

class UserRepository
{
    private array $users;

    public function __construct()
    {
        $this->users = [
            new User(
                "jd@connect-en-play.nl",
                "JD1",
                "John Doe",
                "JD1",
                UserRole::EMPLOYEE,
                [new Address("London", "SW1A 2AA", "Downing Street 10")]
            ),
            new User(
                "admin@connect-en-play.nl",
                "AD1",
                "Admin",
                "AD1",
                UserRole::ADMINISTRATOR,
                [new Address("Amsterdam", "postcode", "Kalverstraat 3")]
            ),
            new User(
                "test@test.nl",
                "TE1",
                "Test",
                "TEst",
                UserRole::ADMINISTRATOR,
                [new Address("Test", "6969 FU", "Test straat 69")]
            ),
            new User(
                "connectenplayfan1998@fastmail.com",
                "CO1",
                "Connie Enp",
                "CO1",
            )
        ];
    }

    /**
     * @throws Exception
     */
    public function getUser(string $emailOrId): User
    {
        /**
         * @var User $user
         */
        foreach ($this->users as $user) {
            if ($user->getEmail() === $emailOrId || $user->getId() === $emailOrId) {
                return $user;
            }
        }
        throw new Exception("Gebruiker niet gevonden."); // gooit een error als de gebruiker niet gevonden is
    }
}
