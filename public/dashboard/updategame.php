<?php
require_once '/var/www/php/Shop/Controllers/GameController.php';
require_once '/var/www/php/Shared/Guards/EmployeeGuard.php';

$guard = new EmployeeGuard();

// Alleen POST toegestaan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = urlencode("Ongeldige methode, alleen POST is toegestaan");
    header("Location: /dashboard/zoekgame.php?error=$error");
    exit;
}

$guard->redirectIfNotAllowed();

try {
    // Controleer of ID is gezet en  of het een nummer is
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception("Ongeldig of ontbrekend ID");
    }

    // Maak controller aan
    $controller = new GameController();

    // Roep updateGame aan en ontvang de bijgewerkte game terug
    $controller->updateGame();

    // Gebruik ID uit POST om terug te keren naar de juiste game
    $id = (int)$_POST['id'];
    header("Location: /dashboard/editgame.php?id=$id&success=1");
    exit;

} catch (Exception $e) {
    // Encode foutmelding en redirect naar editpagina met ID
    $message = urlencode($e->getMessage());
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
    header("Location: /dashboard/editgame.php?error=$message&id=$id");
    exit;
}
