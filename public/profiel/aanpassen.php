<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/UserController.php';

$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    // Als de gebruiker zijn gegevens aanpast, haal de gegevens uit de $_POST en maak er een variabele van.
    $controller->updateUserInfo($_POST['userId'], $_POST);
}
$error = null;
$success = null;
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}
/**
 * @var User $user
 */
if (isset($user)): ?>
    <script src="/js/userUpdateValidation.js"></script>
    <img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
    <section id="profile-container" class="flex justify-center py-15">
        <div class="col-12 flex align-center flex-col">
            <h1 class="heading text-center">Profiel - Aanpassen</h1>
            <div class="flex gap-20 flex-row align-center">
                <a href="/profiel.php" class="button">Mijn profiel</a>
                <a href="/profiel/bestellingen.php" class="button">Bestellingen</a>
                <a href="/profiel/facturen.php" class="button">Facturen</a>
                <a href="/profiel/aanpassen.php" class="button active">Profiel aanpassen</a>
            </div>
            <div id="error-box" class="mb-col-12 col-12 flex justify-center pt-10" <?php echo $error ? '' : 'style="display: none;"'; ?>>
                <p class="error-message text-center p-10"><?php echo $error ?></p>
            </div>
            <div id="success-box" class="mb-col-12 col-12 flex justify-center pt-10" <?php echo $success ? '' : 'style="display: none;"'; ?>>
                <p class="success-message text-center p-10"><?php echo $success ?></p>
            </div>
            <form action="/profiel/aanpassen.php" method="post">
                <div class="col-12 flex flex-row gap-20">
                    <div class="col-6 flex flex-col">
                        <h2 class="text-center">Info</h2>
                        <input type="hidden" name="userId" value="<?php echo $user->getId() ?>">
                        <div>
                            <label for="name">Naam:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()) ?>" class="input" required>
                        </div>
                        <div>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()) ?>" class="input" required>
                        </div>
                        <div>
                            <label for="password">Wachtwoord:</label>
                            <input type="password" id="password" name="password" class="input">
                        </div>
                        <div>
                            <label for="password-repeat">Herhaal wachtwoord:</label>
                            <input type="password" id="password-repeat" name="password-repeat" class="input">
                        </div>
                    </div>
                    <div class="col-6 flex flex-col">
                        <h2 class="text-center">Adres</h2>
                        <div>
                            <label for="postal-code">Postcode:</label>
                            <input type="text" id="postal-code" name="postal-code" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getPostalCode()) : "" ?>" class="input" required>
                        </div>
                        <div>
                            <label for="street-name">Straat naam:</label>
                            <input type="text" id="street-name" name="street-name" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getStreet()) : "" ?>" class="input" required>
                        </div>
                        <div>
                            <label for="house-number">Huisnummer:</label>
                            <input type="text" id="house-number" name="house-number" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getHouseNumber()) : "" ?>" class="input" required>
                        </div>
                        <div>
                            <label for="city">Stad:</label>
                            <input type="text" id="city" name="city" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getCity()) : "" ?>" class="input" required>
                        </div>
                    </div>
                    <button type="submit" class="button">Opslaan</button>
                </div>
            </form>
        </div>
    </section>
<?php endif;
require_once '/var/www/php/Shared/footer.php'; ?>