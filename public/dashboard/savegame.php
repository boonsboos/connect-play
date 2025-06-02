<?php
require_once '/var/www/php/Shop/Controllers/GameController.php';

try {
    $controller = new GameController();
    $controller->addGame();
} catch (Exception $e) {
    // Redirect met foutmelding
    $message = urlencode($e->getMessage());
    header("Location: add-game.php?error=$message");
    exit;
}
