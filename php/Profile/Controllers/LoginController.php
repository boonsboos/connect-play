<?php

require_once './php/Shared/Controller.php';
require_once './php/Profile/DataAccess/UserRepository.php';
require_once './php/Profile/Domain/User.php';

class LoginController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(string $email, string $enteredPassword): void
    {
        try {
            $user = $this->userRepository->getUser($email);

            if (password_verify($enteredPassword, $user->getPassword())) {
                session_start();

                $_SESSION['userId'] = $user->getId(); // vul de $_SESSION met de userId uit de user
                header("Location: /profiel.php", true, 303);
                die();
            } else {
                throw new Exception("Wachtwoord is onjuist.");
            }
        } catch (Exception $exception) {
            // vervang de inlog pagina met de login pagina met een error message
            header("Location: /login.php?error=" . urlencode($exception->getMessage()), true, 303);
            die();
        }
    }
}
