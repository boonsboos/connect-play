<?php

require_once "/var/www/php/Shared/Guards/Guard.php";
require_once "/var/www/php/Profile/DataAccess/UserRepository.php";

class AdminGuard extends Guard {

    public function allowed(): bool
    {
        if (!isset($_SESSION["userId"])) {
            header("Location: /login.php");
            return false;
        }

        // check dat de gebruiker administrator is
        try {
            $userRepo = new UserRepository();
            $user = $userRepo->getUser($_SESSION["userId"]);

            if ($user->getRole() === UserRole::ADMINISTRATOR) {
                return true;
            }
        } catch (Exception $e) {
            // TODO: logging
        }

        // als we een error krijgen of de gebruiker is geen administrator,
        // is de gebruiker niet toegestaan
        return false;
    }
}

