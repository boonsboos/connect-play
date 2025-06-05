<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';

$controller = new GameController();

// Array waarin zoekresultaten worden opgeslagen
$searchResults = [];

// Haal de zoekterm op uit de querystring (bijv. ?q=zoekterm), of gebruik een lege string als er niets is opgegeven
$results = $_GET['q'] ?? '';

// Als er een zoekterm is ingevoerd, voer dan de zoekactie ui
if (!empty($results)) {
    try {
        $searchResults = $controller->searchGamesByName($results);
    } catch (Exception $e) {
        // Toon een foutmelding als de zoekactie mislukt
        echo "<p style='color:red;'>Fout bij zoeken: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- HTML: Zoekformulier + resultaten -->
<div class="p-50">
    <h1>Zoek een game</h1>
     <!-- Zoekformulier: verzendt via GET zodat de zoekterm zichtbaar blijft in de URL -->
    <form method="get" action="">
        <input type="text" name="q" value="<?= htmlspecialchars($results) ?>" placeholder="Zoek op naam..." required>
        <button type="submit">Zoeken</button>
    </form>

    <?php 
    
    if (!empty($searchResults)): ?>
         <!-- Als er zoekresultaten zijn, toon ze als een lijst -->
        <h2>Resultaten</h2>
        <ul>
            <?php foreach ($searchResults as $game): ?>
                <li>
                    <!-- Toon informatie van de game -->
                    <strong><?= htmlspecialchars($game->getName()) ?></strong><br/>
                    - Beschrijving <?= $game->getDescription() ?> <br/>
                    - Prijs: €<?= number_format($game->getPrice(), 2) ?><br/>
                    - Duur: <?= $game->getDuration() ?> minuten<br/>
                    - Moeilijkheid: <?= htmlspecialchars($game->getDifficulty()) ?><br/>
                    - Op voorraad: <?= $game->getLeftInStock() ?> stuks <br/>
                    - Spelers: <?= $game->getPlayers() ?> <br/>
                    <!-- Link naar bewerkpagina met game-ID in de URL -->
                    <a href="/dashboard/editgame.php?id=<?= $game->getId() ?>">Bewerken</a><br/><br/><br/><br/>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($results): ?>
        <!-- Als er geen resultaten zijn maar er wel gezocht is, geef melding -->
        <p>Geen games gevonden voor: <em><?= htmlspecialchars($results) ?></em></p>
    <?php endif; ?>
</div>

<?php require_once '/var/www/php/Shared/footer.php'; ?>
