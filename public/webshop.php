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
<div class="webshop-container mb-flex-col">
    <div class="col-2 productfilter product-accent-border flex justify-center">
        <aside class="filter-sectie">
            <h3>Filteren</h3>
            <form class="flex flex-col">
				<div>
					<label for="zoekfunctie">Zoek Product:</label>
					<input type="text" id="zoekfunctie" name="search" placeholder="Zoek Product" value="<?php echo $_GET["search"] ?? ""; ?>">
				</div>
				<div>
					<label for="maxspelers">Max. Spelers</label>
					<input type="range" id="maxspelers" name="maxspelers" min="2" max="16" step="2" value="<?php echo $_GET["maxspelers"] ?? '16'; ?>">
				</div>
				<div>
					<label for="minspelers">Min. Spelers</label>
					<input type="range" id="minspelers" name="minspelers" min="2" max="16" step="2" value="<?php echo $_GET["minspelers"] ?? '2'; ?>">
				</div>
				<div>
                	<button class="button" type="submit">Filter nu</button>
				</div>
            </form>
        </aside>
    </div>
	<div class="product-accent-border mb-col-12 col-9">

        <?php if ($controller->getTotalOfGames() == 0): ?>
		<div class="flex justify-center align-center py-50">
			<h1 class="text-center">We hebben het product dat je zoekt niet kunnen vinden... :(</h1>
		</div>
        <?php endif;?>

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
		</div>

		<!--
			Elke link naar de volgende pagina moet de filter parameters meegeven
		-->

		<!-- Paginering -->
		<div class="pagination py-10 px-10">
			<!-- Wanneer pagina groter dan 1 is, link naar vorige pagina-->
			<?php if ($currentPage > 1): ?>
				<a href="?page=<?php echo $currentPage - 1 . $controller->getFilterParams(); ?>">Vorige</a>
			<?php endif; ?>
			<!-- Berekenen van het aantal pagina's -->
			<?php for ($i = 1; $i <= $controller->getTotalPages(); $i++): ?>
				<a href="?page=<?php echo $i . $controller->getFilterParams(); ?>"
				<!-- make current page bold-->
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