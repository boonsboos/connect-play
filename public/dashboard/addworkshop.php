<?php
require_once "/var/www/php/Shared/Guards/EmployeeGuard.php";
//
//$employeeGuard = new EmployeeGuard();
//$employeeGuard->redirectIfNotAllowed();

require_once "/var/www/php/Shared/header.php";

// implementation imports
require_once "/var/www/php/Shop/Controllers/WorkshopController.php";
require_once "/var/www/php/Shop/Domain/Workshop.php";
require_once "/var/www/php/Shop/Domain/Game.php";

$workshopController = new WorkshopController();
?>

<image class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding"></image>
<section class="flex flex-col col-6 offset-3 mb-col-12 py-15" id="add-workshop-section">
    <h1>Workshop toevoegen</h1>
	<form class="flex flex-col col-6">
		<label for="gameId">Spel</label>
		<select
			name="game"
			id="gameId"
		>
			<option value="">Kiezen</option>
			<?php foreach ($workshopController->getGamesWithoutWorkshops() as $game):?>
                <option value="<?= $game->getId() ?>"><?= $game->getName()?> (<?= $game->getPlayers()?> spelers)</option>
            <?php endforeach; ?>
		</select>

    <?php if($workshopController->gameProvided()): ?>
		<label for="players">Spelers</label>
        <input id="players" type="number" min="2" step="2">

		<label for></label>
		<input type="number">

	    <button action="submit">Toevoegen</button>
	<?php endif;?>
	</form>

	<script src="/js/dashboard/addworkshop.js"></script>
</section>

<?php
require_once "/var/www/php/Shared/footer.php";