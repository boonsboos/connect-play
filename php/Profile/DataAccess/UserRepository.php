<?php

require_once './php/Profile/Domain/User.php';

class UserRepository
{
    private array $users;

    public function __construct() {
        $this->users = [
            new User(
                "jd@connect-en-play.nl",
                "JD1", "John Doe",
                UserRole::EMPLOYEE,
                [new Address("London", "Downing Street", "10")]
            ),
            new User(
                "admin@connect-en-play.nl",
                "AD1", "Admin",
                UserRole::ADMINISTRATOR,
                [new Address("Amsterdam", "Kalverstraat", "3")]
            ),
            new User(
                "connectenplayfan1998@fastmail.com",
                "CO1", "Connie Enp"
            )
        ];
    }

    /**
     * @throws Exception
     */
    public function getUser(string $email): User {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        throw new Exception("User not found");
    }
}