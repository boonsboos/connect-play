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
    // Als er een spatsie staat tussen de nummers & letters wordt die weggehaald
    $postalcode = str_replace(' ', '', $_POST['postalcode'] ?? '');
    $housenumber = $_POST['housenumber'] ?? '';
    $city = $_POST['city'] ?? '';

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
<script src="js/userValidation.js"></script>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="register-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post">
        <div id="error-box" class="mb-col-12 col-12 error-message"></div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-9 col-8">
                <label>Voornaam</label>
                <input type="text" class="input" name="firstname" value="" required />
            </div>

            <div class="mb-col-3 col-2">
                <label>Tussenvoegsel</label>
                <input type="text" class="input" name="infix" value="" />
            </div>
        </div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-9">
                <label>Achternaam</label>
                <input type="text" class="input" name="lastname" value="" required />
            </div>
        </div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-5">
                <label for="email">E-mail:</label>
                <input class="input" type="email" name="email" placeholder="email@example.com" />
            </div>

            <div class="mb-col-12 col-5">
                <label>Herhaal Email</label>
                <input type="email" class="input" name="repeat_email" value="" required />
            </div>
        </div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-5">
                <label>Wachtwoord</label>
                <input type="password" class="input" name="password" required />
            </div>

            <div class="mb-col-12 col-5">
                <label>Herhaal wachtwoord</label>
                <input type="password" class="input" name="repeat_password" required />
            </div>
        </div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-9">
                <label>Straat</label>
                <input type="text" class="input" name="streetname" required />
            </div>
        </div>

        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-5 col-2">
                <label>Postcode</label>
                <input type="text" class="input" name="postalcode" placeholder="1234AB" value="" required />
            </div>

            <div class="mb-col-5 col-2">
                <label>Huisnummer</label>
                <input type="text" class="input" name="housenumber" value="" required />
            </div>

            <div class="mb-col-12 col-6">
                <label>Plaats</label>
                <input type="text" class="input" name="city" value="" required />
            </div>
        </div>
        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-6 col-6">
                <button class="button" type="submit">Registreer</button>
            </div>
        </div>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>