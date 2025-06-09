<?php

require_once "/var/www/php/Shared/header.php";

require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed();

require_once "/var/www/php/Shop/Controllers/WorkshopController.php";
require_once "/var/www/php/Shop/Domain/Game.php";
require_once "/var/www/php/Shop/Domain/Workshop.php";

$workshopController = new WorkshopController();
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<?php if (isset($_GET["status"])): ?>
	<section id="message-section" class="flex col-4 offset-4 mb-col-12 py-15">
        <?php if ($_GET["status"] == "success"): ?>
			<div class="status-success col-12 flex flex-col">
				<h2 class="text-center">Gelukt!</h2>
				<p class="text-center">
					Workshop is
                    <?php if ($workshopController->gameProvided()): ?>
						bijgewerkt voor <?= $workshopController->getGame((int) $_GET["gameId"])->getName() ?>
                    <?php else: ?>
						bijgewerkt!
                    <?php endif; ?>
				</p>
			</div>
        <?php else: ?>
			<div class="status-error col-12 flex flex-col">
				<h2 class="text-center">Helaas</h2>
				<p class="text-center">Het is niet gelukt om de workshop te bewerken voor
					<!-- als de game is meegegeven, laat de naam zien -->
                    <?php if ($workshopController->gameProvided()): ?>
                        <?= $workshopController->getGame((int) $_GET["gameId"])->getName() ?>...
                    <?php else: ?>
						deze game...
                    <?php endif; ?>
				</p>
			</div>
        <?php endif; ?>
	</section>
<?php endif; ?>

<section class="flex flex-col col-6 offset-3 mb-col-12 py-15" id="update-workshop-section">
	<h1 class="text-center">Workshop bewerken</h1>
	<form class="flex flex-col offset-3 col-6 justify-center" action="/dashboard/workshop/update.php" id="workshop-form" method="post">
		<label for="gameId">Spel</label>
		<select name="gameId" id="gameId">
			<!-- lege optie als default -->
			<option value="">Kiezen</option>
			<?php foreach ($workshopController->getGamesWithWorkshops() as $game):?>
				<option value="<?= $game->getId() ?>"><?= $game->getName()?> (<?= $game->getPlayers()?> spelers)</option>
			<?php endforeach; ?>
		</select>

		<?php if($workshopController->gameProvided()) { ?>

			<?php $workshop = $workshopController->getWorkshop($_GET["gameId"]); ?>
			<label for="minplayers">Min. spelers</label>
			<input name="minplayers" id="minplayers" type="number" min="2" step="2" required value="<?= $workshop->getMinSize(); ?>">

			<label for="maxplayers">Max. spelers</label>
			<input name="maxplayers" id="maxplayers" type="number" min="2" step="2" required value="<?= $workshop->getMaxSize(); ?>">

			<label for="duration">Duur in stappen van 30 minuten</label>
			<input name="duration" id="duration" type="number" min="30" step="30" required value="<?= $workshop->getDuration(); ?>">

			<label for="price">Prijs</label>
			<div class="flex flex-nowrap">
				<span>€</span>
				<input name="price" id="price" inputmode="numeric" placeholder="12,34" required class="col-12" value="<?= $workshop->getPrice() ?>">
			</div>

			<input type="submit" value="Bijwerken">
		<?php }?>
	</form>

	<script src="/js/dashboard/addworkshop.js"></script>
</section>

<?php
require_once "/var/www/php/Shared/footer.php";