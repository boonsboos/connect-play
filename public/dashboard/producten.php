<?php

//require_once "/var/www/php/Shared/employee-only.php";
require_once "/var/www/php/Shared/header.php";
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="producten-container" class="flex flex-col col-6 offset-3 py-10">
	<h1 class="text-center">Producten</h1>

	<section id="game-section">
		<h2>Games</h2>
		<div class="product-accent-border flex flex-row py-30 px-30">
			<a href="/dashboard.php" class="dashboardButton text-center col-5">
				<?= __("dashboard.products.game.add") ?>
			</a>
			<a href="/dashboard.php" class="dashboardButton text-center offset-2 col-5">
				<?= __("dashboard.products.game.search") ?>
			</a>
		</div>
	</section>

	<section id="workshop-section">
		<h2>Workshops</h2>
		<div class="product-accent-border flex flex-row py-30 px-30">
			<a href="/dashboard/workshop/addworkshop.php" class="dashboardButton text-center col-5">
				<?= __("dashboard.products.workshop.add") ?>
			</a>
			<a href="/dashboard/workshop/editworkshop.php" class="dashboardButton text-center offset-2 col-5">
				<?= __("dashboard.products.workshop.edit") ?>
			</a>
		</div>
	</section>
</section>


<?php
require_once "/var/www/php/Shared/footer.php";