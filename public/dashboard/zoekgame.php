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

<div class="search-layout">
  <!-- ZOEKEN LINKS -->
  <aside class="search-sidebar">
    <h1>Zoek een game</h1>
    <form method="get" action="">
      <input type="text" name="q" value="<?= htmlspecialchars($results) ?>" placeholder="Zoek op naam...">
      <button type="submit">Zoeken</button>
    </form>
  </aside>

  <!-- RESULTATEN RECHTS -->
  <main class="search-results-area">
    <?php if (!empty($searchResults)): ?>
      <h2>Resultaten</h2>
      <div class="results-grid">
        <?php foreach ($searchResults as $game): ?>
        <?php
            $imageUrl = trim($game->getImageUrl() ?? '');
            $validImage = (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL));
            $finalImage = $validImage
                ? htmlspecialchars($imageUrl)
                : 'https://via.placeholder.com/80x80?text=Geen+afbeelding';
        ?>
          <div class="result-card">
            <div class="card-content">
              <img src="<?= $finalImage ?>" class="game-image" alt="Afbeelding van <?= htmlspecialchars($game->getName()) ?>">
              <div class="game-info">
                <strong><?= htmlspecialchars($game->getName()) ?></strong><br/>
                - Beschrijving: <?= $game->getDescription() ?><br/>
                - Prijs: €<?= number_format($game->getPrice(), 2) ?><br/>
                - Duur: <?= $game->getDuration() ?> minuten<br/>
                - Moeilijkheid: <?= htmlspecialchars($game->getDifficulty()) ?><br/>
                - Op voorraad: <?= $game->getLeftInStock() ?> stuks<br/>
                - Spelers: <?= $game->getPlayers() ?>
              </div>
            </div>
            <div class="card-button">
              <a href="/dashboard/editgame.php?id=<?= $game->getId() ?>" class="search-button">Bewerken</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php elseif (!empty($_GET['q'])): ?>
      <p>Geen games gevonden voor: <em><?= htmlspecialchars($results) ?></em></p>
    <?php endif; ?>
  </main>
</div>

<?php require_once '/var/www/php/Shared/footer.php'; ?>