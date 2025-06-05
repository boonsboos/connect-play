<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';

try {
    $controller = new GameController();
    $game = $controller->getGame(); // Haalt game op aan de hand van $_GET['id']

    // Controleer of de game bestaat
    echo "<p>Game gevonden: " . htmlspecialchars($game->getName()) . "</p>";
    } catch (Exception $e) {
    // Foutafhandeling: toon een foutmelding en stop de uitvoering
    echo "<p style='color:red;'>Fout: " . $e->getMessage() . "</p>";
    require_once '/var/www/php/Shared/footer.php';
    exit;
}
?>


<div>
<h1>Game bewerken</h1>

<!-- Formulier om een bestaande game te updaten -->
<form method="POST" action="updategame.php">
    <div>

    <!-- Verborgen veld met het game-ID -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($game->getId()) ?>"><br/>

    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($game->getName()) ?>" required><br/>

    <label for="players">Aantal spelers:</label>
    <input type="number" id="players" name="players" value="<?= $game->getPlayers() ?>" required><br/>

    <label for="price">Prijs:</label>
    <input type="number" step="0.01" id="price" name="price" value="<?= $game->getPrice() ?>" required><br/>

    <label for="duration">Duur:</label>
    <input type="number" id="duration" name="duration" value="<?= $game->getDuration() ?>" required><br/>

    <label for="description">Beschrijving:</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($game->getDescription()) ?></textarea><br/>

    <label for="difficulty">Moeilijkheid:</label>
    <select id="difficulty" name="difficulty">
        <?php foreach (['Gemakkelijk', 'Matig', 'Moeilijk'] as $level): ?>
            <option value="<?= $level ?>" <?= $game->getDifficulty() === $level ? 'selected' : '' ?>><?= $level ?></option>
        <?php endforeach; ?>
    </select><br/>

    <label for="left_in_stock">Op voorraad:</label>
    <input type="number" id="left_in_stock" name="left_in_stock" value="<?= $game->getLeftInStock() ?>" required><br/>

    <label for="image_url">Afbeeldings-URL:</label>
    <input type="url" id="image_url" name="image_url" value="<?= htmlspecialchars($game->getImageUrl()) ?>" oninput="updatePreview()"><br/>

    <img id="preview" src="<?= htmlspecialchars($game->getImageUrl()) ?>" style="max-width:300px; margin-top:10px;"><br/><br/>

    <button type="submit">Opslaan</button>
    </div>
</form>

</div>

<script>
function updatePreview() {
    const url = document.getElementById('image_url').value.trim();
    const img = document.getElementById('preview');
    if (url.match(/^https?:\/\/.+\.(jpg|jpeg|png|gif|webp)$/i)) {
        img.src = url;
        img.style.display = 'block';
    } else {
        img.style.display = 'none';
    }
}
</script>

<?php
require_once '/var/www/php/Shared/footer.php';
?>
