<?php

require_once '../php/Shared/header.php';
require_once '../php/Profile/Controllers/UserController.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'] ?? '';
    $infix = $_POST['infix'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $repeatEmail = $_POST['repeat_email'] ?? '';
    $password = $_POST['password'] ?? '';
    $repeatPassword = $_POST['repeat_password'] ?? '';
    $streetname = $_POST['streetname'] ?? '';
    $postalcode = $_POST['postalcode'] ?? '';
    $housenumber = $_POST['housenumber'] ?? '';
    $city = $_POST['city'] ?? '';

    // Validatie (simpele voorbeelden)
    if ($email !== $repeatEmail) {
        die("Emails komen niet overeen.");
    }

    if ($password !== $repeatPassword) {
        die("Wachtwoorden komen niet overeen.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $fullName = trim("$firstname $infix $lastname");

    // Maak Address en User objecten aan
    $addressObj = new Address($postalcode, $housenumber, $streetname, $city);
    $user = new User($email, uniqid(), $fullName, $hashedPassword, UserRole::CUSTOMER, [$addressObj]);

    $controller = new UserController();
    $controller->register($user);

    header("Location: login.php?message=" . urlencode("Registratie succesvol!"));
    exit();
}
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="register-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post" action="register.php">
        <div class="col-9">
            <label>Voornaam</label>
            <input type="text" name="firstname" value="" required />
        </div>

        <div class="col-3">
            <label>Tussenvoegsel</label>
            <input type="text" name="infix" value="" required />
        </div>

        <div class="col-12">
            <label>Achternaam</label>
            <input type="text" name="lastname" value="" required />
        </div>

        <div class="col-12">
            <label>Email</label>
            <input type="email" name="email" value="" placeholder="email@example.com" required />
        </div>

        <div class="col-12">
            <label>Herhaal Email</label>
            <input type="email" name="repeat_email" value="" required />
        </div>

        <div class="col-12">
            <label>Wachtwoord</label>
            <input type="password" name="password" required />
        </div>

        <div class="col-12">
            <label>Herhaal wachtwoord</label>
            <input type="password" name="repeat_password" required />
        </div>

        <div class="col-12">
            <label>Straat</label>
            <input type="text" name="streetname" required />
        </div>

        <div class="col-6">
            <label>Postcode</label>
            <input type="text" name="postalcode" value="" required />
        </div>

        <div class="col-6">
            <label>Huisnummer</label>
            <input type="text" name="housenumber" value="" required />
        </div>

        <div class="col-12">
            <label>Plaats</label>
            <input type="text" name="city" value="" required />
        </div>

        <button type="submit">Registreer</button>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>