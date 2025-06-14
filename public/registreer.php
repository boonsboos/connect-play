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

    $controller = new UserController();
    $controller->register([
        'email' => $email,
        'fullName' => $fullName,
        'password' => $hashedPassword,
        'postalcode' => $postalcode,
        'housenumber' => $housenumber,
        'streetname' => $streetname,
        'city' => $city,
    ]);
}
?>
<script src="js/userValidation.js"></script>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="register-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post">

        <div id="error-box" class="mb-col-12 col-12 error-message"></div>

        <div class="mb-col-10 col-8 flex justify-center">
            <div class="mb-col-12 col-12">
                <label for="firstname">Voornaam</label>
                <input type="text" class="input" name="firstname" value="" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="infix">Tussenvoegsel</label>
                <input type="text" class="input" name="infix" value="" />
            </div>

            <div class="mb-col-12 col-12">
                <label for="lastname">Achternaam</label>
                <input type="text" class="input" name="lastname" value="" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center py-15">
            <div class="mb-col-12 col-12">
                <label for="email">E-mail:</label>
                <input class="input" type="email" name="email" placeholder="email@example.com" />
            </div>

            <div class="mb-col-12 col-12">
                <label for="repeat_email">Herhaal Email</label>
                <input type="email" class="input" name="repeat_email" value="" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="password">Wachtwoord</label>
                <input type="password" class="input" name="password" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="repeat_password">Herhaal wachtwoord</label>
                <input type="password" class="input" name="repeat_password" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center">
            <div class="mb-col-12 col-12">
                <label for="streetname">Straat</label>
                <input type="text" class="input" name="streetname" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="postalcode">Postcode</label>
                <input type="text" class="input" name="postalcode" placeholder="1234AB" value="" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="housenumber">Huisnummer</label>
                <input type="text" class="input" name="housenumber" value="" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="city">Plaats</label>
                <input type="text" class="input" name="city" value="" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center pt-15">
            <div class="mb-col-8 col-8">
                <button class="button" type="submit">Registreer</button>
            </div>
        </div>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>