<?php
require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shop/Controllers/GameController.php";
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<div class="flex justify-center pt-10 pb-10">
  <h1>Nieuwe game toevoegen</h1>
</div>

<form method="POST" action="savegame.php" class="edit-game-form">
  <div class="form-columns">
    <div class="form-left">
      <div class="form-row">
        <label for="name">Naam:</label>
        <input type="text" id="name" name="name" placeholder="Naam van het spel" required>
      </div>

      <div class="form-row">
        <label for="players">Aantal spelers:</label>
        <input type="number" id="players" name="players" placeholder="Voor hoeveel spelers" required>
      </div>

      <div class="form-row">
        <label for="price">Prijs: (€)</label>
        <input type="number" step="0.01" id="price" name="price" placeholder="De prijs" required>
      </div>

      <div class="form-row">
        <label for="duration">Duur (min):</label>
        <input type="number" id="duration" name="duration" placeholder="Duur van spel" required>
      </div>

      <div class="form-row">
        <label for="description">Beschrijving:</label>
        <textarea id="description" name="description" placeholder="Beschrijving van spel" required></textarea>
      </div>

      <div class="form-row">
        <label for="difficulty">Moeilijkheid:</label>
        <select id="difficulty" name="difficulty">
          <option value="Gemakkelijk">Gemakkelijk</option>
          <option value="Matig">Gemiddeld</option>
          <option value="Moeilijk">Moeilijk</option>
        </select>
      </div>

      <div class="form-row">
        <label for="left_in_stock">Op voorraad:</label>
        <input type="number" id="left_in_stock" name="left_in_stock" placeholder="Voorraad van spel" required>
      </div>

      <div class="form-row">
        <label for="image_url">Afbeeldings-URL:</label>
        <input type="url" id="image_url" name="image_url" placeholder="https://..." oninput="updatePreview()">
      </div>
    </div>

    <div class="form-right">
      <img id="preview" src="https://firstbenefits.org/wp-content/uploads/2017/10/placeholder-1024x1024.png" alt="Voorbeeldafbeelding">
    </div>
  </div>

  <div class="flex justify-center form-buttons">
    <button type="submit">Toevoegen</button>
    <button type="reset" onclick="toonPopup('🧹 Formulier is geleegd.')">Reset</button>
    <button type="button" onclick="window.location.href='/dashboard/zoekgame.php'">Terug</button>
  </div>
</form>

<!-- Popup -->
<div id="popup" class="popup-overlay" style="display: none;">
  <div class="popup-box">
    <p id="popup-message"></p>
    <button onclick="sluitPopup()">OK</button>
  </div>
</div>

<!-- Externe JavaScript -->
<script src="/js/dashboard/game-form.js"></script>

<?php if (isset($_GET['success'])): ?>
  <script>window.addEventListener("DOMContentLoaded", () => toonPopup("✅ Game succesvol toegevoegd!"));</script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <script>window.addEventListener("DOMContentLoaded", () => toonPopup("❌ <?= htmlspecialchars(urldecode($_GET['error'])) ?>"));</script>
<?php endif; ?>

<?php require_once "/var/www/php/Shared/footer.php"; ?>
