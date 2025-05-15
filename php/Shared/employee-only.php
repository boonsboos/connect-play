<?php

require_once "../php/Shared/header.php";
require_once '../php/Profile/DataAccess/UserRepository.php';

if (!isset($_SESSION["userId"])) {
    header("Location: /login.php");
    return;
}

// verify the user is actually an employee or admin
try {
    $userRepo = new UserRepository();
    $user = $userRepo->getUser($_SESSION["userId"]);

    // users are not allowed to log in
    if ($user->getRole() === UserRole::CUSTOMER) {
        throw new Exception("Unauthorized access!");
    }
} catch(Exception $e) {
    header("Location: /login.php");
}