<script src="/js/filter.js"></script>

<?php
// Inclusie van noodzakelijke bestanden
require_once '../php/Shared/header.php';

require_once '../php/Shop/controllers/WebshopController.php';

$controller = new WebshopController(); // limit : 6

$currentPage = $controller->getCurrentPage();
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />
<div class="webshop-container">
    <div class="col-2 productfilter flex justify-center">
        <aside class="filter-sectie">
            <h3>Filter:</h3>
            <form>
                <label for="search">Zoek Product:</label>
                <input type="text" id="zoekfunctie" name="search" placeholder="Zoek Product">
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
        <?php foreach ($controller->getGames() as $game): ?> <!-- // limit : 6 -->
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
            <!-- Wanneer pagina groter dan 1 is, link naar vorige pagina-->
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo $currentPage - 1; ?>">Vorige</a>
        <?php endif; ?>
        <!-- Berekenen van het aantal pagina's -->
        <?php for ($i = 1; $i <= $controller->getTotalPages(); $i++): ?> <!-- // limit : 6 -->
            <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
        <!-- Wanneer de huidige pagina kleiner is dan het totaal aantal pagina's, link naar volgende pagina-->
        <?php if ($currentPage < $controller->getTotalPages()): ?> <!-- // limit : 6 -->
            <a href="?page=<?php echo $currentPage + 1; ?>">Volgende</a>
        <?php endif; ?>
    </div>
    </div>
</div>

<?php require_once '../php/Shared/footer.php'; ?>