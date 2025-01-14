<?php

require_once './php/Shared/Controller.php';
require_once './php/Shared/View.php';
require_once './php/Profile/DataAccess/UserRepository.php';

class LoginController extends Controller
{
    private array $encryptedPasswords;
    private UserRepository $userRepository;

    public function __construct(View $view)
    {
        parent::__construct($view);

        $this->encryptedPasswords = [
            "JD1" => password_hash("JD1", PASSWORD_DEFAULT),
            "AD1" => password_hash("AD1", PASSWORD_DEFAULT),
            "CO1" => password_hash("CO1", PASSWORD_DEFAULT),
        ];

        $this->userRepository = new UserRepository();
    }

    public function login(string $email, string $enteredPassword): void
    {
        try {
            $user = $this->userRepository->getUser($email);

            foreach ($this->encryptedPasswords as $userId=> $userPassword) {
                if ($user->getId() === $userId) {
                    $hashedPassword = password_hash($enteredPassword, PASSWORD_DEFAULT);

                    if (password_verify($userPassword, $hashedPassword)) {
                        header("Location: /contact.php", true, 307);
                    } else {
                        throw new Exception("wrongPassword");
                    }
                }
            }

            throw new Exception("noAccount");
        } catch (Exception $exception) {
            // redirect met query parameter
            header("Location: /login.php?result=" . $exception->getMessage(), true, 307);
        }
    }
}