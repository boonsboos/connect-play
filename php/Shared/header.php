<?php
require_once '/var/www/php/Shared/debug.php';
require_once '/var/www/php/Profile/DataAccess/UserRepository.php';
require_once '/var/www/php/Shared/Language/Language.php';

$language = new Language();
function __($key): string // verkorte versie voor de vertaal functie
{
    global $language; // gebruik de globale $language variabele
    return $language->translate($key); // vertaal de sleutel naar de huidige taal
}


// check of userId in de sessie zit
if (isset($_SESSION["userId"])) {
    $userId = $_SESSION["userId"];
    $repo = new UserRepository();
    try {
        $user = $repo->getUser($userId);
    } catch (Exception $e) { // vang de exception op als de gebruiker niet gevonden is
        session_destroy();
        // vervang de huidige pagina met de login pagina
        header("Location: /login.php", true, 303);
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connect & Play</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/style.css" />
</head>

<body>
    <header class="flex justify-center">
        <div id="header-container" class="flex mb-col-12 col-6 justify-center align-center py-10">
            <!-- make the logo and title a home page link -->
            <a id="home-link" href="/index.php">
                <div class="flex justify-center col-12 align-center">
                    <img src="/images/c&p-logo.svg" alt="Connect & Play logo" />
                    <p class="">Connect & Play</p>
                </div>
            </a>

            <div id="page-links" class="flex offset mb-col-12">
                <a href="/diensten.php"><?= __('nav.services') ?></a>
                <a href="/over-ons.php"><?= __('nav.about') ?></a>
                <a href="/webshop.php"><?= __('nav.webshop') ?></a>
                <a href="/contact.php"><?= __('nav.contact') ?></a>
                <?php if (isset($user)): ?>
                    <?php if ($user->getRole() === UserRole::EMPLOYEE || $user->getRole() === UserRole::ADMINISTRATOR): ?>
                        <a href="/dashboard.php"><?= __('nav.dashboard') ?></a>
                    <?php endif; ?>
                    <a href="/profiel.php"><?= __('nav.profile') ?></a>
                    <a href="/logout.php"><?= __('nav.logout') ?></a>
                <?php else: ?>
                    <a href="/login.php"><?= __('nav.login') ?></a>
                <?php endif; ?>
                <?php if ($language->getLanguage() === 'nl'): ?>
                    <a href="?lang=en">EN</a>
                <?php else: ?>
                    <a href="?lang=nl">NL</a>
                <?php endif; ?>
            </div>
        </div>
    </header>