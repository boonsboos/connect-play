<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';

$controller = new GameController();

$searchResults = [];
$results = $_GET['q'] ?? '';

try {
    if (!empty($results)) {
        $searchResults = $controller->searchGamesByName($results);
    } else {
        // Geen zoekterm opgegeven: laad alle games
        $searchResults = $controller->getGames();
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Fout bij het ophalen van games: " . $e->getMessage() . "</p>";
}
?>

<div class="mb-col-12 search-layout flex justify-center PT-50 PB-30">
  <!-- ZOEKEN LINKS -->
  <aside class="search-sidebar col-2 mb-col-12 flex-column">
    <h2>Zoek een game</h2>
    <form method="get" action="">
      <input type="text" name="q" value="<?= htmlspecialchars($results) ?>" 
      placeholder="Zoek op naam...">
      <button type="submit">Zoeken</button>
    </form>
  </aside>

  <!-- RESULTATEN RECHTS -->
  <main class="search-results-area col-9 mb-col-12">
    <?php if (!empty($searchResults)): ?>
      <h2>Resultaten</h2>
      <div class="results-grid">
        <?php foreach ($searchResults as $game):
            if (!($game instanceof Game)) {
                continue; // Zorg ervoor dat we alleen Game objecten verwerken
            } 
            $imageUrl = trim($game->getImageUrl() ?? '');
            $finalImage = (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL))
                ? htmlspecialchars($imageUrl)
                : 'https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png';
        ?>
          <div class="result-card">
            <div class="card-content">
              <img src="<?= $finalImage ?>" class="game-image" alt="Afbeelding van <?= htmlspecialchars($game->getName()) ?>">
              <div class="game-info"><ul class="game-details">
                <strong><?= htmlspecialchars($game->getName()) ?></strong><br/>
                <li>Beschrijving: <?= htmlspecialchars($game->getDescription()) ?></li>
                <li>Prijs: €<?= htmlspecialchars(number_format($game->getPrice(), 2)) ?></li>
                <li>Duur: <?= htmlspecialchars($game->getDuration()) ?> minuten</li>
                <li>Moeilijkheid: <?= htmlspecialchars($game->getDifficulty()) ?></li>
                <li>Op voorraad: <?= htmlspecialchars($game->getLeftInStock()) ?> stuks</li>
                <li>Spelers: <?= htmlspecialchars($game->getPlayers()) ?></li>
                </ul>
              </div>
            </div>
            <div class="card-button">
              <a href="/dashboard/editgame.php?id=<?= htmlspecialchars($game->getId()) ?>" class="search-button">Bewerken</a>
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