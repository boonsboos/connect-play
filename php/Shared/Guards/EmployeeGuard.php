<?php

require_once "/var/www/php/Shared/Guards/Guard.php";
require_once "/var/www/php/Profile/DataAccess/UserRepository.php";

class EmployeeGuard extends Guard {

    public function allowed(): bool
    {
        if (!isset($_SESSION["userId"])) {
            return false;
        }

        // check dat de gebruiker geen customer is
        try {
            $userRepo = new UserRepository();
            $user = $userRepo->getUser($_SESSION["userId"]);

            if ($user->getRole() !== UserRole::CUSTOMER) {
                return true;
            }
        } catch (Exception $e) {
            // TODO: logging
        }

        // als we een error krijgen of de gebruiker is een customer,
        // is de gebruiker niet toegestaan
        return false;
    }
}

