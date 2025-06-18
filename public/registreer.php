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
$successKey = '';
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successKey = "register.success";
}
?>
<script src="js/userValidation.js"></script>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="register-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post">

        <div id="error-box" class="mb-col-12 col-12 error-message"></div>
        <div id="success-box" class="mb-col-12 col-12 success-message" style="display: <?php echo $successKey ? 'block' : 'none'; ?>;"><?= __($successKey); ?></div>

        <div class="mb-col-10 col-8 flex justify-center">
            <div class="mb-col-12 col-12">
                <label for="firstname"><?= __('register.firstname'); ?></label>
                <input type="text" class="input" name="firstname" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="infix"><?= __('register.infix'); ?></label>
                <input type="text" class="input" name="infix" />
            </div>

            <div class="mb-col-12 col-12">
                <label for="lastname"><?= __('register.lastname'); ?></label>
                <input type="text" class="input" name="lastname" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center py-15">
            <div class="mb-col-12 col-12">
                <label for="email"><?= __('register.email'); ?></label>
                <input class="input" type="email" name="email" placeholder="email@example.com" />
            </div>

            <div class="mb-col-12 col-12">
                <label for="repeat_email"><?= __('register.repeat_email'); ?></label>
                <input type="email" class="input" name="repeat_email" required />

            </div>

            <div class="mb-col-12 col-12">
                <label for="password"><?= __('register.password'); ?></label>
                <input type="password" class="input" name="password" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="repeat_password"><?= __('register.repeat_password'); ?></label>

                <input type="password" class="input" name="repeat_password" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center">
            <div class="mb-col-12 col-12">
                <label for="streetname"><?= __('register.streetname'); ?></label>
                <input type="text" class="input" name="streetname" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="postalcode"><?= __('register.postalcode'); ?></label>
                <input type="text" class="input" name="postalcode" placeholder="1234AB" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="housenumber"><?= __('register.housenumber'); ?></label>
                <input type="text" class="input" name="housenumber" required />
            </div>

            <div class="mb-col-12 col-12">
                <label for="city"><?= __('register.city'); ?></label>
                <input type="text" class="input" name="city" required />
            </div>
        </div>

        <div class="mb-col-10 col-8 flex justify-center pt-15">
            <div class="mb-col-8 col-8">
                <button class="button" type="submit"><?= __('register.submit'); ?></button>
            </div>
        </div>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>