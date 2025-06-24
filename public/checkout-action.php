<?php
/*
    checkout-action.php is de tussenpersoon tussen JS en PHP
*/
require_once '/var/www/php/Shop/Controllers/ShoppingCartController.php';

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Voor het afrekenen dient u ingelogd te zijn.']);
    return;
}

$ShoppingCartController = new ShoppingCartController();
$ShoppingCartController->dispatch();
