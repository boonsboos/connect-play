<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die(400); // 400: bad request
}

require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
if (!$adminGuard->allowed()) {
    die(403); // 403: unauthorized
}

require_once "/var/www/php/Shop/Controllers/WorkshopController.php";

$workshopController = new WorkshopController();

// geef ook de game ID terug als we een error krijgen
try {
    if ($workshopController->createWorkshop()) {
        header("Location: /dashboard/workshop/addworkshop.php?status=success");
    } else {
        header("Location: /dashboard/workshop/addworkshop.php?gameId=" . $_POST["gameId"] . "&status=invalid");
    }
} catch (Exception $exception) {
    header("Location: /dashboard/workshop/addworkshop.php?gameId=" . $_POST["gameId"] . "&status=error");
}