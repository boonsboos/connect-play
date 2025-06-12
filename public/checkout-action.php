<?php

require_once '../php/Shop/Controllers/CheckoutController.php';

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'Voor het afrekenen dient u ingelogd te zijn.']);
    return;
}

$checkoutController = new CheckoutController();
$checkoutController->dispatch();