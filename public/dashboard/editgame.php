<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<?php
try {
    $controller = new GameController();
    $game = $controller->getGame(); // Haalt game op via ?id=
} catch (Exception $e) {
    echo "<p style='color:red;'>Fout: " . $e->getMessage() . "</p>";
    require_once '/var/www/php/Shared/footer.php';
    exit;
}
?>

<div class="flex justify-center pt-10 pb-10">
  <h1>Game bewerken</h1>
</div>

<form method="POST" action="updategame.php" class="edit-game-form justify-center">
  <div class="form-columns">
    <div class="form-left">
      <input type="hidden" name="id" value="<?= htmlspecialchars($game->getId()) ?>">

      <div class="form-row">
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($game->getName()) ?>" required>
      </div>

      <div class="form-row">
        <label for="players">Aantal spelers:</label>
        <input type="number" id="players" name="players" value="<?= htmlspecialchars($game->getPlayers()) ?>" required>
      </div>

      <div class="form-row">
        <label for="price">Prijs:</label>
        <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($game->getPrice()) ?>" required>
      </div>

      <div class="form-row">
        <label for="duration">Duur (min):</label>
        <input type="number" id="duration" name="duration" value="<?= htmlspecialchars($game->getDuration()) ?>" required>
      </div>

      <div class="form-row">
        <label for="description">Beschrijving:</label>
        <textarea id="description" name="description"><?= htmlspecialchars($game->getDescription()) ?></textarea>
      </div>

      <div class="form-row">
        <label for="difficulty">Moeilijkheid:</label>
        <select id="difficulty" name="difficulty">
          <?php foreach (['Gemakkelijk', 'Matig', 'Moeilijk'] as $level): ?>
            <option value="<?= $level ?>" <?= htmlspecialchars($game->getDifficulty()) === $level ? 'selected' : '' ?>><?= $level ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-row">
        <label for="left_in_stock">Op voorraad:</label>
        <input type="number" id="left_in_stock" name="left_in_stock" value="<?= htmlspecialchars($game->getLeftInStock()) ?>" required>
      </div>

      <div class="form-row">
        <label for="image_url">Afbeeldings-URL:</label>
        <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($game->getImageUrl()) ?>" oninput="updatePreview()">
      </div>
    </div>

    <div class="form-right">
      <?php
      $imageUrl = trim($game->getImageUrl() ?? '');
      $finalImage = (!empty($imageUrl) && filter_var($imageUrl, FILTER_VALIDATE_URL))
        ? htmlspecialchars($imageUrl)
        : 'https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png';
      ?>
      <img id="preview" src="<?= $finalImage ?>" alt="Voorbeeldafbeelding">
    </div>
  </div>

  <div class="flex justify-center form-buttons">
    <button type="submit">Opslaan</button>
    <button type="reset" onclick="toonPopup('🧹 Formulier is geleegd.')">Reset</button>
    <button type="button" onclick="window.location.href='/dashboard/zoekgame.php'">Terug</button>
  </div>
</form>

<div id="popup" class="popup-overlay" style="display: none;">
  <div class="popup-box">
    <p id="popup-message"></p>
    <button onclick="sluitPopup()">OK</button>
  </div>
</div>

<script src="/js/dashboard/game-form.js"></script>

<?php if (isset($_GET['success'])): ?>
  <script>window.addEventListener("DOMContentLoaded", () => toonPopup("✅ Game succesvol opgeslagen!"));</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <script>window.addEventListener("DOMContentLoaded", () => toonPopup("❌ <?= htmlspecialchars(urldecode($_GET['error'])) ?>"));</script>
<?php endif; ?>

<?php require_once '/var/www/php/Shared/footer.php'; ?>
