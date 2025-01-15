<?php

require_once './php/Shared/Controller.php';
require_once './php/Shared/View.php';
require_once './php/Profile/DataAccess/UserRepository.php';

class LoginController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(View $view)
    {
        parent::__construct($view);

        $this->userRepository = new UserRepository();
    }

    public function login(string $email, string $enteredPassword): void
    {
        try {
            $user = $this->userRepository->getUser($email);


            if (password_verify($enteredPassword, $user->getPassword())) {
                session_start();

                $_SESSION['uuid'] = $user->getId();
                header("Location: /contact.php", true, 303);
                die();
            } else {
                throw new Exception("Wachtwoord is onjuist.");
            }
        } catch (Exception $exception) {
            // redirect met query parameter
            header("Location: /login.php?error=" . urlencode($exception->getMessage()), true, 303);
            die(); // or exit(); so the script stops executing
        }
    }
}
