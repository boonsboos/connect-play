<?php

require_once '../php/Shared/header.php';
require_once '../php/Profile/Controllers/UserController.php';

$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email']; // haal email uit $_POST en maak er een variabele van.
        $password = $_POST['password'];

        $controller->forgotPassword($email);
    }
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}

?>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="login-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post">
        <?php if (isset($error)): ?>
            <div class="mb-col-12 col-12 flex justify-center pb-30">
                <p class="error-message text-center p-10"><?php echo $error ?></p>
            </div>
        <?php endif; ?>
        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-5">
                <label for="email">E-mail:</label>
                <input id="email" class="input" type="text" name="email" placeholder="email@example.com" />
            </div>
        </div>

        <div class="send-message mb-col-6 col-5 pt-30">
            <button class="button" type="submit">Verstuur</button>
        </div>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>