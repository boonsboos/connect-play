<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/UserController.php';

$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
    // Als de gebruiker zijn gegevens aanpast, haal de gegevens uit de $_POST en maak er een variabele van.
    $controller->updateUser($_POST['userId'], $_POST);
}

if (isset($_GET['error'])) {
    $loginError = $_GET['error'];
}
/**
 * @var User $user
 */
if (isset($user)): ?>
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
            <div class="col-6 flex flex-row justify-between">
                <form action="/profiel/aanpassen.php" method="post" class="col-6 flex flex-col">
                    <h2 class="text-center">Info</h2>
                    <input type="hidden" name="type" value="information">
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
                        <input type="password" id="password" name="password" class="input" required>
                    </div>
                    <div>
                        <label for="password-">Herhaal wachtwoord:</label>
                        <input type="password" id="password" name="password" class="input" required>
                    </div>
                    <button type="submit" class="button">Opslaan</button>
                </form>
                <form action="/profiel/aanpassen.php" method="post" class="col-6 flex flex-col">
                    <h2 class="text-center">Adres</h2>
                    <input type="hidden" name="type" value="address">
                    <input type="hidden" name="userId" value="<?php echo $user->getId() ?>">
                    <div>
                        <label for="postal">Postcode:</label>
                        <input type="text" id="name" name="name" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getPostalCode()) : "" ?>" class="input" required>
                    </div>
                    <div>
                        <label for="street">Straat naam:</label>
                        <input type="street" id="street" name="street" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getStreet()) : "" ?>" class="input" required>
                    </div>
                    <div>
                        <label for="street">Huisnummer:</label>
                        <input type="street" id="street" name="street" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getHouseNumber()) : "" ?>" class="input" required>
                    </div>
                    <div>
                        <label for="street">Stad:</label>
                        <input type="street" id="street" name="street" value="<?php echo count($user->getAddresses()) > 0 ? htmlspecialchars($user->getAddresses()[0]->getCity()) : "" ?>" class="input" required>
                    </div>
                    <button type="submit" class="button">Opslaan</button>
                </form>
            </div>
        </div>
    </section>
<?php endif;
require_once '/var/www/php/Shared/footer.php'; ?>