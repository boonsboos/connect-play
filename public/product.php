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
			<img src="images/dienst_3.jpg" alt="Product afbeelding" style="width: 100%; object-fit: cover; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
			
		</div>

		<!-- Rechterkant: Details + prijs + button -->
		<div class="col-6 flex flex-col px-15">
			<h3 class="pb-10"><?= $game->getName(); ?></h3>
			<p class="pb-15">
				<?= $game->getDescription(); ?>
			</p>

			<div class="flex flex-row align-center pb-15" style="gap: 1rem;">
				<h4>€ <?= $game->getPrice(); ?></h4>
				<button class="button px-10" style="width: auto;">Toevoegen</button>
			</div>

            <div class="flex py-10">
                <p>Score:</p>
				<span style="color: gold;">★ ★ ★ ★ ☆</span>
			</div>

			<!-- Specificaties tabel -->
			<table style="width: 100%; border-collapse: collapse;">
				<tr>
					<th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--box-1);">Specificatie</th>
					<th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--box-1);">Waarde</th>
				</tr>
				<tr>
					<td style="padding: 10px;">Moeilijkheidsgraat</td>
					<td style="padding: 10px;"><?= $game->getDifficulty(); ?></td>
				</tr>
				<tr>
					<td style="padding: 10px;">Duur</td>
					<td style="padding: 10px;"><?= $game->getDuration(); ?> minuten</td>
				</tr>
				<tr>
					<td style="padding: 10px;">Spelers</td>
					<td style="padding: 10px;">Maximaal <?= $game->getPlayers(); ?> spelers</td>
				</tr>
			</table>

            
		</div>
	</div>
</div>

</div>
<?php
require_once '../php/Shared/footer.php';
?>