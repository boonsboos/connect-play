<?php
require_once "/var/www/php/Shared/header.php";
?>

<div class="p-50 flex justify-content-center">
<h1>Nieuwe game toevoegen</h1>

<!-- Laat succesbericht zien als er via de URL een success parameter is meegegeven -->
<?php if (isset($_GET['success'])): ?>
    <div style="color: green;">✅ Game succesvol toegevoegd.</div>
<?php endif; ?>

<!-- Formulier voor het toevoegen van een nieuwe game -->
<form action="savegame.php" method="post">
    <div>
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required>
    <br/>
    <label for="players">Aantal spelers:</label>
    <input type="number" id="players" name="players" required>
    <br/>
    <label for="price">Prijs:</label>
    <input type="number" step="0.01" id="price" name="price" required>
    <br/>
    <label for="duration">Duur (in minuten):</label>
    <input type="number" id="duration" name="duration" required>
    <br/>
    <label for="description">Beschrijving:</label>
    <textarea id="description" name="description" required></textarea>
    <br/>
    <label for="difficulty">Moeilijkheidsgraad:</label>
    <select id="difficulty" name="difficulty">
        <option value="Gemakkelijk">Gemakkelijk</option>
        <option value="Matig">Gemiddeld</option>
        <option value="Moeilijk">Moeilijk</option>
    </select>
    <br/>
    <label for="left_in_stock">Aantal op voorraad:</label>
    <input type="number" id="left_in_stock" name="left_in_stock" required>
    <br/>
    <label for="image_url">Afbeeldings-URL:</label>
    <input type="url" id="image_url" name="image_url" placeholder="https://..." oninput="updatePreview()">
    <br/>
    <img id="preview" style="display:none; max-width:300px; margin-top:10px;" alt="Voorbeeld afbeelding">
    <button type="submit">Game toevoegen</button>
</form>
</div>
</div>

<!-- JavaScript voor live preview van afbeelding zodra geldige URL is ingevoerd -->
<script>
function updatePreview() {
    // Haal de waarde van de URL input op en trim eventuele spaties
    const url = document.getElementById('image_url').value.trim();
    // Zoek het preview element op
    const img = document.getElementById('preview');
    // Alleen tonen als URL eindigt op een geldige afbeeldingsextensie
    if (url.match(/^https?:\/\/.+\.(jpg|jpeg|png|gif|webp)$/i)) {
        // Stel de src van de afbeelding in op de URL
        img.src = url;
        // Zorg dat de afbeelding zichtbaar is
        img.style.display = 'block';
    } else {
        // Verberg de afbeelding als de URL ongeldig is
        img.style.display = 'none';
    }
}
</script>

<?php
require_once "/var/www/php/Shared/footer.php";
?>