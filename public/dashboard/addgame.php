<?php

//require_once "/var/www/php/Shared/employee-only.php";
require_once "/var/www/php/Shared/header.php";
?>

<div class="p-50 flex justify-content-center">
<h1>Nieuwe game toevoegen</h1>

<?php if (isset($_GET['success'])): ?>
    <div style="color: green;">✅ Game succesvol toegevoegd.</div>
<?php endif; ?>

<form action="savegame.php" method="post">
    <div>
<form method="POST" action="addgame.php">
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
        <option value="easy">Gemakkelijk</option>
        <option value="medium">Gemiddeld</option>
        <option value="hard">Moeilijk</option>
    </select>
    <br/>
    <label for="left_in_stock">Aantal op voorraad:</label>
    <input type="number" id="left_in_stock" name="left_in_stock" required>
    <br/>
    <button type="submit">Game toevoegen</button>
</form>
</div>
</div>
<?php
require_once "/var/www/php/Shared/footer.php";
?>