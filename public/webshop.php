<?php
// Inclusie van noodzakelijke bestanden
require_once '../php/Shared/header.php';
require_once '../php/Shop/DataAccess/WebshopRepository.php';
require_once '../php/Shop/controllers/WebshopController.php';
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />
<div class="webshop-container">
    <div class="col-2 productfilter flex justify-center">
        <aside class="filter-sectie">
            <h3>Filter:</h3>
            <form>
                <label for="search">Zoek Product:</label>
                <input type="text" id="search" name="search" placeholder="Zoek Product">
                <h4>Soort product:</h4>
                <label><input type="checkbox" name="bordspellen"> Bordspellen</label><br>
                <label><input type="checkbox" name="kaartspellen"> Kaartspellen</label><br>
                <label><input type="checkbox" name="workshops"> Workshops</label>
                <h4>Leeftijdsgrens:</h4>
                <input type="range" name="age" min="0" max="99">
                <button type="submit">Filter nu</button>
            </form>
        </aside>
    </div>
    <div class="productlijst mb-col-12 col-9 flex justify-center">
         <!-- Productlijstweergave -->
        <?php foreach ($games as $game): ?>
            <div class="game-card">
                <!-- Weergave van een individuele game-kaart -->
                <h3><?php echo htmlspecialchars($game->getName()); ?></h3>
                <p>Prijs: €<?php echo htmlspecialchars($game->getPrice()); ?></p>
                <p>Spelers: <?php echo htmlspecialchars($game->getPlayers()); ?></p>
                <p>Beschrijving: <?php echo htmlspecialchars($game->getDescription()); ?></p>
                <hr/>
            </div>
        <?php endforeach; ?>

        <!-- Paginering -->
        <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo $currentPage - 1; ?>">Vorige</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>">Volgende</a>
        <?php endif; ?>
    </div>
    </div>
</div>
<?php

