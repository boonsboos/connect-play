<?php
// Inclusie van noodzakelijke bestanden
require_once '../php/Shared/header.php';

require_once '../php/Shop/controllers/WebshopController.php';

$controller = new WebshopController();

$currentPage = $controller->getCurrentPage();

if (isset($_GET["search"]) && isset($_GET["maxspelers"]) && isset($_GET["minspelers"])) {
	$controller->setFilterSettings($_GET);
}

$controller->fetchGames();
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />
<div id="webshopContainer" class="mb-flex-col flex flex-nowrap py-15 px-15">
    <div id="productfilter" class="col-2 product-accent-border flex justify-center">
        <aside class="filter-sectie mb-col-12">
            <h3>Filteren</h3>
            <form class="flex mb-flex-col">
				<div class="flex flex-col col-12">
					<label for="zoekfunctie">Zoeken</label>
					<input type="text" id="zoekfunctie" name="search" placeholder="Jouw spel" value="<?php echo $_GET["search"] ?? ""; ?>">
				</div>
				<div class="flex flex-col col-12">
					<label for="maxspelers">Max. Spelers: <span id="maxspelers-value"></span></label>
					<input type="range" id="maxspelers" name="maxspelers" min="2" max="16" step="2" value="<?php echo $_GET["maxspelers"] ?? '16'; ?>">
				</div>
				<div class="flex flex-col col-12">
					<label for="minspelers">Min. Spelers: <span id="minspelers-value"></span></label>
					<input type="range" id="minspelers" name="minspelers" min="2" max="16" step="2" value="<?php echo $_GET["minspelers"] ?? '2'; ?>">
				</div>
                	<button class="button" type="submit">Filteren</button>
            </form>
        </aside>
    </div>

	<!-- late include zodat deze na het inladen van de filters wordt uitgevoerd-->
	<script src="js/playerFilters.js"></script>

	<div class="product-accent-border mb-col-12 col-10">

        <?php if ($controller->getTotalOfGames() == 0): ?>
		<div class="flex justify-center align-center py-50">
			<h1 class="text-center">We hebben het product dat je zoekt niet kunnen vinden... :(</h1>
		</div>
        <?php endif;?>

		<div id="productlijst" class="mb-col-12 col-12 flex justify-center px-15 py-15">
			 <!-- Productlijstweergave -->

			<?php foreach ($controller->getGames() as $game): ?>
				<div class="game-card" onclick="window.location='/product.php?id=<?php echo $game->getId() ?>'">
					<!-- Weergave van een individuele game-kaart -->
					<h3 class="text-center"><?php echo htmlspecialchars($game->getName()); ?></h3>
					<p>Prijs: €<?php echo htmlspecialchars($game->getPrice()); ?></p>
					<p>Spelers: <?php echo htmlspecialchars($game->getPlayers()); ?></p>
					<p>Beschrijving: <?php echo htmlspecialchars($game->getDescription()); ?></p>
					<hr/>
				</div>
			<?php endforeach; ?>
		</div>

		<!--
			Elke link naar de volgende pagina moet de filter parameters meegeven
		-->

		<div id="pagination" class="py-10 px-10 text-center">
			<!-- Wanneer pagina groter dan 1 is, link naar vorige pagina-->
			<?php if ($currentPage > 1): ?>
				<a href="?page=<?php echo $currentPage - 1 . $controller->getFilterParams(); ?>">Vorige</a>
			<?php endif; ?>
			<!-- Berekenen van het aantal pagina's -->
			<?php for ($i = 1; $i <= $controller->getTotalPages(); $i++): ?>
				<a href="?page=<?php echo $i . $controller->getFilterParams(); ?>">
				<!-- highlight de huidige pagina in bold-->
				<?php if ($i === $currentPage): ?>
					<b><u><?php echo $i; ?></u></b>
				<?php else: ?>
					<?php echo $i; ?>
				<?php endif; ?>
				</a>
			<?php endfor; ?>
			<!-- Wanneer de huidige pagina kleiner is dan het totaal aantal pagina's, link naar volgende pagina-->
			<?php if ($currentPage < $controller->getTotalPages()): ?>
				<a href="?page=<?php echo $currentPage + 1 . $controller->getFilterParams(); ?>">Volgende</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php require_once '../php/Shared/footer.php'; ?>