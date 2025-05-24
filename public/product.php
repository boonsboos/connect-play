<?php
// Inclusie van noodzakelijke bestanden
require_once '../php/Shared/header.php';
require_once '../php/Shop/controllers/GameController.php';

$controller = new GameController();
$game = $controller->getGame();

if (!$game instanceof Game) {
	echo "<p>Er is iets misgegaan bij het ophalen van de game.</p>";
	exit;
}
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />
<div class="container flex justify-center">

	<div class="container flex justify-center py-30">
		<div class="col-10 flex flex-row">
			<div class="col-6 flex flex-col align-center pl-30">
				<img src="images/dienst_3.jpg" class="product-img" alt="Product afbeelding" />

			</div>

			<!-- Rechterkant: Details + prijs + button -->
			<div class="col-6 flex flex-col px-15">
				<h3 class="pb-10"><?= $game->getName(); ?></h3>
				<p class="pb-15">
					<?= $game->getDescription(); ?>
				</p>

				<!-- De functie number_format() vervangd de punt naar een komma  -->
				<div class="flex flex-row align-center pb-15">
					<h4>€ <?= number_format($game->getPrice(), 2, ',', ''); ?></h4>
					<button class="button px-10" style="width: auto;">Toevoegen</button>
				</div>

				<div class="flex py-10">
					<p>Score:</p>
					<span style="color: gold;">★ ★ ★ ★ ☆</span>
				</div>

				<!-- Specificaties tabel -->
				<table style="border-collapse: collapse;">
					<tr>
						<th class="p-10 text-left table-head">Specificatie</th>
						<th class="p-10 text-left table-head">Waarde</th>
					</tr>
					<tr>
						<td class="p-10">Moeilijkheidsgraat</td>
						<td class="p-10"><?= $game->getDifficulty(); ?></td>
					</tr>
					<tr>
						<td class="p-10">Duur</td>
						<td class="p-10"><?= $game->getDuration(); ?> minuten</td>
					</tr>
					<tr>
						<td class="p-10">Spelers</td>
						<td class="p-10">Maximaal <?= $game->getPlayers(); ?> spelers</td>
					</tr>
				</table>


			</div>
		</div>
	</div>

</div>
<?php
require_once '../php/Shared/footer.php';
?>