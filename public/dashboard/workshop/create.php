<?php
// geef ook de game ID terug als we een error krijgen
$locationHeader = "Location: /dashboard/workshop/addworkshop.php?gameId=" . $_POST["gameId"] . "&status=";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: /dashboard/workshop/addworkshop.php?status=invalid");
    die();
}

require_once "/var/www/php/Shared/header.php"; // voor de sessie
require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed("/dashboard.php");

require_once "/var/www/php/Shop/Controllers/WorkshopController.php";

$workshopController = new WorkshopController();

try {
    if ($workshopController->createWorkshop()) {
        header($locationHeader . "success");
    } else {
        header($locationHeader . "invalid");
    }
} catch (Exception $exception) {
    header($locationHeader . "error");
}