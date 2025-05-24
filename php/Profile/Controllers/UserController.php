<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Profile/DataAccess/UserRepository.php';
require_once '/var/www/php/Profile/Domain/User.php';
require_once '/var/www/php/Profile/Domain/Address.php';

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function register(User $user): void
    {
        try {
            $name = $user->getName();
            $email = $user->getEmail();
            $password = $user->getPassword();

            $address = $user->getAddresses()[0];

            $streetname = $address->getStreetName();
            $postalcode = $address->getPostalCode();
            $housenumber = $address->getHouseNumber();
            $city = $address->getCity();

            /**
             * Backend Validatie
             */

            // Controleer of gebruiker al bestaat
            try {
                $this->userRepository->getUser($user->getEmail());
                // Als bovenstaande niet faalt, bestaat de gebruiker al
                throw new Exception("E-mailadres is al in gebruik.");
            } catch (Exception $e) {
                if ($e->getMessage() !== "Gebruiker niet gevonden.") {
                    // Als het een andere exception is dan "gebruiker niet gevonden", gooi die door
                    throw $e;
                }

                if (empty($name)) throw new Exception("Naam is verplicht.");

                // Controller of het een valide e-mailadres is
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Voer een geldig e-mailadres in.");
                }

                if (strlen($password) < 8) {
                    throw new Exception("Wachtwoord moet minstens 8 tekens bevatten.");
                }

                if (empty($streetname)) throw new Exception("Straatnaam is verplicht.");

                if (!preg_match('/^\d{4}[A-Z]{2}$/', $postalcode)) {
                    throw new Exception("Voer een geldige Nederlandse postcode in (bijv. 1234AB).");
                }

                if (empty($housenumber)) throw new Exception("Huisnummer is verplicht.");
                if (empty($city)) throw new Exception("Plaats is verplicht.");

                // Als alle validatie is gedaan wordt de gebruiker toegevoegd aan de database hier:
                $this->userRepository->addUser($user);
            }
        } catch (Exception $e) {
            header("Location: /registreer.php?error=" . urlencode($e->getMessage()), true, 303);
            die();
        }
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
            if (count($user->getAddresses()) === 0) {
                $user->setAddresses([new Address('', '', '', '')]);
            }
            $user->getAddresses()[0]->setPostalCode($data['postal-code']);
            $user->getAddresses()[0]->setHouseNumber($data['house-number']);
            $user->getAddresses()[0]->setStreetName($data['street-name']);
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
