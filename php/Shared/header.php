<?php session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>C&P: Contact</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header class="flex justify-center">
        <div id="header-container" class="flex mb-col-12 col-6 justify-center align-center py-10">
            <!-- make the logo and title a home page link -->
            <a id="home-link" href="index.php">
                <div class="flex justify-center col-12 align-center">
                    <img src="images/c&p-logo.svg" alt="Connect & Play logo" />
                    <p class="">Connect & Play</p>
                </div>
            </a>

            <div id="page-links" class="flex offset mb-col-12">
                <a href="diensten.php">Diensten</a>
                <a href="over-ons.php">Over Ons</a>
                <a href="contact.php">Contact</a>
                <a href="login.php">Login</a>
            </div>
        </div>
    </header>