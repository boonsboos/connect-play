<?php

require_once '../php/Profile/Domain/User.php';

class UserRepository
{
    private array $users;

    public function __construct()
    {
        $this->users = [
            new User(
                "jd@connect-en-play.nl",
                "JD1ID",
                "John Doe",
                "JD1",
                UserRole::EMPLOYEE,
                [new Address("London", "SW1A 2AA", "Downing Street 10")]
            ),
            new User(
                "admin@connect-en-play.nl",
                "AD1ID",
                "Admin",
                "AD1",
                UserRole::ADMINISTRATOR,
                [new Address("Amsterdam", "2234 AD", "Kalverstraat 3")]
            ),
            new User(
                "cardmaster@cardcore.nl",
                "COID",
                "Fred Langmaker",
                "Welkom01",
                UserRole::ADMINISTRATOR,
                [new Address("Goes", "6969 FU", "Opwerkstraaat 69")]
            ),
            new User(
                "connectenplayfan1998@fastmail.com",
                "CO1ID",
                "Connie Enp",
                "Welkom02",
                UserRole::ADMINISTRATOR,
                [new Address("Berlijn", "10176", "Friedrichshain 3")]
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
