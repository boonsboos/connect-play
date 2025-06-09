<?php

// geef ook de game ID terug als we een error krijgen
$locationHeader = "Location: /dashboard/workshop/editworkshop.php?gameId=" . $_POST["gameId"] . "&status=";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: /dashboard/workshop/editworkshop.php?status=invalid");
    die();
}

require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed("/dashboard.php");

require_once "/var/www/php/Shop/Controllers/WorkshopController.php";

$workshopController = new WorkshopController();

try {
    if ($workshopController->updateWorkshop()) {
        header($locationHeader . "success");
    } else {
        header($locationHeader . "invalid");
    }
} catch (Exception $exception) {
    header($locationHeader . "error");
}