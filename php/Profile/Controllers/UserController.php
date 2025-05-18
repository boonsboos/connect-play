<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Profile/DataAccess/UserRepository.php';
require_once '/var/www/php/Profile/Domain/User.php';

class UserController extends Controller
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
    public function forgotPassword($email)
    {
        try {
            $this->userRepository->getUser($email);
            // Genereer een unieke code voor de reset link
            $code = bin2hex(random_bytes(6));
            // Simiuleer het verzenden van een e-mail met de reset link
            // In een echte applicatie zou je hier een e-mail verzenden naar de gebruiker
            header("Location: /wachtwoord-reset.php?code=" . urlencode($code) . "&email=" . urlencode($email), true, 303);
            die();
        } catch (Exception $e) {
            header("Location: /wachtwoord-vergeten.php?error=" . urlencode($e->getMessage()), true, 303);
            die();
        }
    }

    public function resetPassword($email, $code, $newPassword)
    {
        try {
            $user = $this->userRepository->getUser(urldecode($email));
            if ($user) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $user->setPassword($hashedPassword);
                $this->userRepository->updateUser($user);
                header("Location: /login.php", true, 303);
                die();
            } else {
                throw new Exception("Wachtwoord reset mislukt.");
            }
        } catch (Exception $e) {
            header("Location: /wachtwoord-reset.php?error=" . urlencode($e->getMessage()), true, 303);
            die();
        }
    }

    public function updateUserInfo($userId, array $data): void
    {
        try {
            $user = $this->userRepository->getUser($userId);

            $user->setName($data['name']);
            $user->setEmail($data['email']);
            $user->getAddresses()[0]->setPostalCode($data['postal-code']);
            $user->getAddresses()[0]->setHouseNumber($data['house-number']);
            $user->getAddresses()[0]->setStreet($data['street-name']);
            $user->getAddresses()[0]->setCity($data['city']);
            if (isset($data['password']) && !empty($data['password'])) {
                $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            }

            $this->userRepository->updateUser($user);
            header("Location: /profiel/aanpassen.php?success=" . urlencode("Profiel succesvol aangepast"), true, 303);
            die();
        } catch (Exception $e) {
            header("Location: /profiel/aanpassen.php?error=" . urlencode($e->getMessage()), true, 303);
            die();
        }
    }
}
