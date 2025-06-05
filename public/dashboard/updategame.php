<?php
require_once '/var/www/php/Shop/Controllers/GameController.php';


// Start met POST-validatie
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = urlencode("Ongeldige methode, alleen POST is toegestaan");
    header("Location: /dashboard/zoekgame.php?error=$error");
    exit;
}

try {
    // Controleer of ID geldig is
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        throw new Exception("Ongeldig of ontbrekend ID");
    }

    // Maak controller aan en voer update-operatie uit met formuliergegevens
    $controller = new GameController();
    $controller->updateGame();

    // Als de update slaagt, redirect met successmelding naar de bewerkpagina
    $id = (int)$_POST['id'];
    header("Location: /dashboard/editgame.php?success=1&id=$id");
    exit;

} catch (Exception $e) {
    // Encodeer de foutmelding zodat deze veilig in de URL kan worden geplaatst
    $message = urlencode($e->getMessage());
    // Als er een geldig ID bekend is, voeg dat toe aan de redirect-URL zodat we terug kunnen keren naar dezelfde game
    $id = isset($_POST['id']) && is_numeric($_POST['id']) ? (int)$_POST['id'] : 0;
    header("Location: /dashboard/editgame.php?error=$message&id=$id");
    exit;
}
