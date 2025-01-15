<?php

require_once './php/Profile/Domain/User.php';

class RepoUser extends User
{
    private string $password;

    public function __construct(string $email, string $id, string $name, string $password, UserRole $role = UserRole::CUSTOMER, array $addresses = [])
    {
        parent::__construct($email, $id, $name, $role, $addresses);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}

class UserRepository
{
    private array $users;

    public function __construct()
    {
        $this->users = [
            new RepoUser(
                "jd@connect-en-play.nl",
                "JD1",
                "John Doe",
                "JD1",
                UserRole::EMPLOYEE,
                [new Address("London", "Downing Street", "10")]
            ),
            new RepoUser(
                "admin@connect-en-play.nl",
                "AD1",
                "Admin",
                "AD1",
                UserRole::ADMINISTRATOR,
                [new Address("Amsterdam", "Kalverstraat", "3")]
            ),
            new RepoUser(
                "test@test.nl",
                "TE1",
                "Test",
                "TEst",
                UserRole::ADMINISTRATOR,
                [new Address("Test", "Test straat", "69")]
            ),
            new RepoUser(
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
    public function getUser(string $email): RepoUser
    {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        throw new Exception("User not found");
    }
}
