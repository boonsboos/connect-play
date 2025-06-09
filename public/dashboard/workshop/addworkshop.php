<?php
require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed();

require_once "/var/www/php/Shared/header.php";

require_once "/var/www/php/Shop/Controllers/WorkshopController.php";
require_once "/var/www/php/Shop/Domain/Game.php";

$workshopController = new WorkshopController();
?>

<image class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding"></image>

<?php if (isset($_GET["status"])): ?>
<section id="message-section" class="flex col-4 offset-4 mb-col-12 py-15">
	<?php if ($_GET["status"] == "success"): ?>
		<div class="status-success col-12 flex flex-col">
			<h2 class="text-center">Gelukt!</h2>
			<p class="text-center">Workshop is aangemaakt</p>
		</div>
	<?php else: ?>
		<div class="status-error col-12 flex flex-col">
			<h2 class="text-center">Helaas</h2>
			<p class="text-center">Het is niet gelukt om een workshop toe te voegen voor
				<!-- als de game is meegegeven, laat de naam zien -->
				<?php if (isset($_GET["gameId"]) && $_GET["gameId"] != ""): ?>
					 <?= $workshopController->getGame((int) $_GET["gameId"])->getName() ?>
				<?php else: ?>
					deze game.
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>
</section>
<?php endif; ?>

<section class="flex flex-col col-6 offset-3 mb-col-12 py-15" id="add-workshop-section">
    <h1 class="text-center">Workshop toevoegen</h1>
	<form class="flex flex-col offset-3 col-6 justify-center" action="/dashboard/workshop/create.php" id="workshop-form" method="post">
		<label for="gameId">Spel</label>
		<select name="gameId" id="gameId">
			<!-- lege optie als default -->
			<option value="">Kiezen</option>
			<?php foreach ($workshopController->getGamesWithoutWorkshops() as $game):?>
                <option value="<?= $game->getId() ?>"><?= $game->getName()?> (<?= $game->getPlayers()?> spelers)</option>
            <?php endforeach; ?>
		</select>

    <?php if($workshopController->gameProvided()): ?>
		<label for="minplayers">Min. spelers</label>
        <input name="minplayers" id="minplayers" type="number" min="2" step="2" required>

		<label for="maxplayers">Max. spelers</label>
		<input name="maxplayers" id="maxplayers" type="number" min="2" step="2" required>

		<label for="duration">Duur (minuten)</label>
		<input name="duration" id="duration" type="number" min="30" step="30" required>

		<label for="price">Prijs</label>
		<div class="flex flex-nowrap">
			<span>€</span>
			<input name="price" id="price" inputmode="numeric" placeholder="12,34" required class="col-12">
		</div>

		<input type="submit" value="Toevoegen">
	<?php endif;?>
	</form>

	<script src="/js/dashboard/addworkshop.js"></script>
</section>

<?php
require_once "/var/www/php/Shared/footer.php";