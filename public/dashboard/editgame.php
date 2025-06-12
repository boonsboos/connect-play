<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';

try {
    $controller = new GameController();
    $game = $controller->getGame();
} catch (Exception $e) {
    echo "<p style='color:red;'>Fout: " . $e->getMessage() . "</p>";
    require_once '/var/www/php/Shared/footer.php';
    exit;
}
?>

<div class="flex justify-center pt-50 pb-30">
  <h1>Game bewerken</h1>
</div>

<form method="POST" action="updategame.php" class="edit-game-form">
  <div class="form-columns">
    <div class="form-left">
      <input type="hidden" name="id" value="<?= htmlspecialchars($game->getId()) ?>">

      <label for="name">Naam:</label>
      <input type="text" id="name" name="name" placeholder="Naam van het spel" value="<?= htmlspecialchars($game->getName()) ?>" required>

      <label for="players">Aantal spelers:</label>
      <input type="number" id="players" name="players" placeholder="Voor hoeveel spelers" value="<?= htmlspecialchars($game->getPlayers()) ?>" required>

      <label for="price">Prijs:</label>
      <input type="number" step="0.01" id="price" name="price" placeholder="De prijs" value="<?= htmlspecialchars($game->getPrice()) ?>" required>

      <label for="duration">Duur:</label>
      <input type="number" id="duration" name="duration" placeholder="Duur van spel" value="<?= htmlspecialchars($game->getDuration()) ?>" required>

      <label for="description">Beschrijving:</label>
      <textarea id="description" name="description" placeholder="Beschrijving van spel"><?= htmlspecialchars($game->getDescription()) ?></textarea>

      <label for="difficulty">Moeilijkheid:</label>
      <select id="difficulty" name="difficulty">
        <?php foreach (['Gemakkelijk', 'Matig', 'Moeilijk'] as $level): ?>
          <option value="<?= $level ?>" <?= htmlspecialchars($game->getDifficulty()) === $level ? 'selected' : '' ?>><?= $level ?></option>
        <?php endforeach; ?>
      </select>

      <label for="left_in_stock">Op voorraad:</label>
      <input type="number" id="left_in_stock" name="left_in_stock" placeholder="Voorraad van spel" value="<?= htmlspecialchars($game->getLeftInStock()) ?>" required>

      <label for="image_url">Afbeeldings-URL:</label>
      <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($game->getImageUrl()) ?>" oninput="updatePreview()">
    </div>

    <div class="form-right">
      <?php
      $imageUrl = trim($game->getImageUrl() ?? '');
      $finalImage = (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL))
          ? htmlspecialchars($imageUrl)
          : 'https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png';
      ?>
      <img id="preview" src="<?= $finalImage ?>" alt="Voorbeeldafbeelding" style="max-width: 300px; margin-top: 10px;">
    </div>
  </div>

  <div class="flex justify-center form-buttons">
    <button type="submit">Opslaan</button>
    <button type="reset" onclick="toonPopup('🧹 Formulier is gereset.')">Reset</button>
    <button type="button" onclick="window.location.href='/dashboard/zoekgame.php'">Terug</button>
  </div>
</form>

<div id="popup" class="popup-overlay" style="display: none;">
  <div class="popup-box">
    <p id="popup-message"></p>
    <button onclick="sluitPopup()">OK</button>
  </div>
</div>

<script>
function updatePreview() {
  const url = document.getElementById('image_url').value.trim();
  const img = document.getElementById('preview');
  const placeholder = 'https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png';

  if (!url) {
    img.src = placeholder;
    img.style.display = 'block';
  } else if (url.match(/^https?:\/\/.+\.(jpg|jpeg|png|gif|webp)$/i)) {
    img.src = url;
    img.style.display = 'block';
  } else {
    img.src = placeholder;
    img.style.display = 'block';
  }
}

// Toon een popup met een bericht
function toonPopup(tekst) {
  const popup = document.getElementById("popup");
  const message = document.getElementById("popup-message");
  message.textContent = tekst;
  popup.style.display = "flex";
}

// Sluit de popup
function sluitPopup() {
  document.getElementById("popup").style.display = "none";
}

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  window.addEventListener('DOMContentLoaded', function () {
    toonPopup("✅ Game succesvol opgeslagen!");
  });
<?php endif;

if (isset($_GET['error'])): ?>
  window.addEventListener('DOMContentLoaded', function () {
    toonPopup("❌ <?= htmlspecialchars(urldecode($_GET['error'])) ?>");
  });
<?php endif; ?>
</script>