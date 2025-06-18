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

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
<div id="webshopContainer" class="mb-flex-col flex flex-nowrap p-15">
	<div id="productfilter" class="col-2 product-accent-border flex justify-center p-15">
		<aside class="filter-sectie mb-col-12">
			<h3><?= __("webshop.filter.heading") ?></h3>
			<form class="flex mb-flex-col">
				<div class="flex flex-col col-12">
					<label for="zoekfunctie"><?= __("webshop.search.label") ?></label>
					<input type="text" id="zoekfunctie" name="search" placeholder="<?= __("webshop.search.placeholder") ?>" value="<?= $_GET["search"] ?? ""; ?>">
				</div>
				<div class="flex flex-col col-12">
					<label for="maxspelers"><?= __("webshop.maxplayers") ?>: <span id="maxspelers-value"></span></label>
					<input type="range" id="maxspelers" name="maxspelers" min="2" max="16" step="2" value="<?= $_GET["maxspelers"] ?? '16'; ?>">
				</div>
				<div class="flex flex-col col-12">
					<label for="minspelers"><?= __("webshop.minplayers") ?>: <span id="minspelers-value"></span></label>
					<input type="range" id="minspelers" name="minspelers" min="2" max="16" step="2" value="<?= $_GET["minspelers"] ?? '2'; ?>">
				</div>
				<button class="button" type="submit"><?= __("webshop.filter.button") ?></button>
			</form>
		</aside>
	</div>

	<!-- late include zodat deze na het inladen van de filters wordt uitgevoerd-->
	<script src="js/playerFilters.js"></script>

	<div class="product-accent-border mb-col-12 col-10">

		<?php if ($controller->getTotalOfGames() == 0): ?>
			<div class="flex justify-center align-center py-50">
				<h1 class="text-center"><?= __("webshop.notfound") ?></h1>
			</div>
		<?php endif; ?>

		<div id="productlijst" class="mb-col-12 col-12 flex justify-center p-15">
			<!-- Productlijstweergave -->

			<?php foreach ($controller->getGames() as $game): ?>
				<div class="game-card p-15" onclick="window.location='/product.php?id=<?= $game->getId() ?>'">
					<!-- Weergave van een individuele game-kaart -->
					<img src="<?= htmlspecialchars($game->getImageUrl()) ?>" alt="Afbeelding<?= htmlspecialchars($game->getName()) ?>" class="game-card-image">
					<h3 class="text-center"><?= htmlspecialchars($game->getName()); ?></h3>
					<hr/>
					<p><?= htmlspecialchars($game->getDescription()); ?></p>
					<p>€<?= htmlspecialchars($game->getPrice()); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<!--
			Elke link naar de volgende pagina moet de filter parameters meegeven
		-->

		<div id="pagination" class="p-10 text-center">
			<!-- Wanneer pagina groter dan 1 is, link naar vorige pagina-->
			<?php if ($currentPage > 1): ?>
				<a href="?page=<?= $currentPage - 1 . $controller->getFilterParams(); ?>">Vorige</a>
			<?php endif; ?>
			<!-- Berekenen van het aantal pagina's -->
			<?php for ($i = 1; $i <= $controller->getTotalPages(); $i++): ?>
				<a href="?page=<?= $i . $controller->getFilterParams(); ?>">
					<!-- highlight de huidige pagina in bold-->
					<?php if ($i === $currentPage): ?>
						<b><u><?= $i; ?></u></b>
					<?php else: ?>
						<?= $i; ?>
					<?php endif; ?>
				</a>
			<?php endfor; ?>
			<!-- Wanneer de huidige pagina kleiner is dan het totaal aantal pagina's, link naar volgende pagina-->
			<?php if ($currentPage < $controller->getTotalPages()): ?>
				<a href="?page=<?= $currentPage + 1 . $controller->getFilterParams(); ?>">Volgende</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php require_once '../php/Shared/footer.php'; ?>