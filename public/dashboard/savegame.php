<?php
require_once '/var/www/php/Shop/Controllers/GameController.php';

try {
    $controller = new GameController();
    $controller->addGame();
} catch (Exception $e) {
    // Als er een fout optreedt (bijv. ontbrekend veld of databasefout)
    // Encodeer de foutmelding zodat die veilig in de URL gebruikt kan worden
    $message = urlencode($e->getMessage());
    // Redirect de gebruiker terug naar het formulier met de foutmelding in de querystring
    header("Location: addgame.php?error=$message");
    exit;
}
