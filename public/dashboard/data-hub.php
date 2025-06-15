<?php
require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shared/Guards/AdminGuard.php";

// check of de gebruiker toestemming heeft
$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed();

require_once "/var/www/php/Shop/Controllers/Dashboard/DataHubController.php";
require_once "/var/www/php/Shared/Database.php";
$error = null;
$success = null;
$controller = new DataHubController(isset($_SESSION['data']) ? $_SESSION['data'] : [], isset($_SESSION['headers']) ? $_SESSION['headers'] : [], isset($_SESSION['uploaded']) ? $_SESSION['uploaded'] : null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    if ($controller->uploadCSVFile($_FILES['csvFile'])) {
        $success = "CSV-bestand succesvol geüpload en verwerkt.";
    } else {
        $error = $controller->getError(); // Haal de foutmelding van de controller op
    }
    if ($controller->isUploaded()) {
        // Sla de headers, data en upload status op in de sessie voor de bevestigingspagina
        $_SESSION['headers'] = $controller->getHeaders(); 
        $_SESSION['data'] = $controller->getData(); 
        $_SESSION['uploaded'] = $controller->isUploaded(); 
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmUpdate'])) {
    if ($controller->updateDatabase()) {
        $success = "Database succesvol bijgewerkt.";
    } else {
        $error = $controller->getError();
    }
    unset($_SESSION['data'], $_SESSION['headers'], $_SESSION['uploaded']); // Verwijder de sessievariabelen na het bijwerken
}

if (isset($_GET['cancel'])) {
    unset($_SESSION['data'], $_SESSION['headers'], $_SESSION['uploaded']); // Verwijder de sessievariabelen als de gebruiker annuleert
    header("Location: /dashboard/data-hub.php"); // Haal ?cancel=1 uit de URL
    exit();
}
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
<h1 class="text-center">DataHub&trade;</h1>
<section id="data-hub-container" class="col-12 flex align-center flex-col">
    <div id="error-box" class="mb-col-12 col-12 flex justify-center pt-10" <?php echo $error ? '' : 'style="display: none;"'; ?>>
        <p class="error-message text-center p-10" <?php echo $error ? 'style="display: block;"' : 'style="display: none;"'; ?>><?php echo $error ?></p>
    </div>
    <div id="success-box" class="mb-col-12 col-12 flex justify-center pt-10" <?php echo $success ? '' : 'style="display: none;"'; ?>>
        <p class="success-message text-center p-10" <?php echo $success ? 'style="display: block;"' : 'style="display: none;"'; ?>><?php echo $success ?></p>
    </div>
    <form class="col-6 pb-15 flex justify-center" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="csvFile">Upload CSV-bestand:</label>
            <input type="file" name="csvFile" id="csvFile" accept=".csv" required>
        </div>
        <button type="submit" class="button">Upload</button>
    </form>
    <?php if ($controller->isUploaded()): ?>
        <?php if (!empty($controller->getData())): ?>
            <div class="col-6 mt-5">
                <h3>Voorbeeld van de ingelezen gegevens:</h3>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <?php foreach ($controller->getHeaders() as $header): ?>
                                <th><?= htmlspecialchars($header) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($controller->getData(), 0, 10) as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p>Alleen de eerste 10 van de <?= count($controller->getData()) ?> rijen worden weergegeven.</p>

                <form method="post" class="mt-4">
                    <input type="hidden" name="confirmUpdate" value="1">
                    <button type="submit" class="button">Update database</button>
                    <a href="/dashboard/data-hub.php?cancel" class="button">Annuleren</a>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mt-4 col-6 offset-3">Het CSV-bestand bevat geen gegevens of kon niet worden gelezen.</div>
        <?php endif; ?>
    <?php endif; ?>
</section>