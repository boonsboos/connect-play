<?php require_once '/var/www/php/Shared/header.php'; ?>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="over-ons-container" class="flex justify-center">
	<div class="wie-zijn-wij-box col-12 flex justify-center">
		<div class="mb-col-12 col-4">
			<h1 class="mb-col-12">
				<?= __('about.who.title'); ?>
			</h1>
			<p class="mb-col-12 py-10">
				<?= __('about.who.text'); ?>
			</p>
		</div>
		<img class="over-ons-img mb-col-12 col-4" src="images/wie-zijn-wij.png"
			alt="Plaatje met tekst: Wie zijn wij?" />
	</div>

	<div class="missie-visie-box col-12 flex justify-center">
		<div class="mb-col-12 col-4">
			<h1 class="mb-col-12">
				<?= __('about.mission.title'); ?>
			</h1>
			<p class="mb-col-12 py-10">
				<?= __('about.mission.text'); ?>
			</p>
		</div>
		<img class="over-ons-img mb-col-12 col-4" src="images/missie.png"
			alt="Plaatje met tekst: Missie en Visie?" />
	</div>
</section>
<?php require_once '/var/www/php/Shared/footer.php'; ?>