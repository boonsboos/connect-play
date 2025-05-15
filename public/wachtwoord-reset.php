<?php

require_once '../php/Shared/header.php';
require_once '../php/Profile/Controllers/UserController.php';

$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new-password']) && isset($_GET['code']) && isset($_GET['email'])) {
        $password = $_POST['new-password'];
        $code = $_GET['code'];
        $email = $_GET['email'];

        $controller->resetPassword($email, $code, $password);
    }
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (isset($_GET['code']) && isset($_GET['email'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];
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
        <?php if (isset($code)): ?>
            <div class="mb-col-12 col-12 flex justify-center pb-30">
                <p>code: <?php echo $code ?></p> <!-- Dit is de code die je moet gebruiken om je wachtwoord te resetten. Komt normaal gesproken uit de email -->
                <p>email: <?php echo $email ?></p>
            </div>
        <?php endif; ?>
        <div class="mb-col-12 col-12 flex justify-center pb-30">
            <div class="mb-col-12 col-5">
                <label for="new-password">Nieuw wachtwoord:</label>
                <input id="new-password" class="input" type="text" name="new-password" placeholder="Nieuw wachtwoord" />
            </div>
        </div>

        <div class="send-message mb-col-6 col-5 pt-30">
            <button class="button" type="submit">Verstuur</button>
        </div>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>