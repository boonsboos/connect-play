<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/ContactController.php';

$controller = new ContactController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->sendContactForm($_POST);
}
$error = null;
$success = null;
if (isset($_GET['error'])) {
	$error = $_GET['error'];
}
if (isset($_GET['success'])) {
	$success = $_GET['success'];
} ?>

<script src="/js/contactValidation.js"></script>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="contact-container" class="flex justify-center">
	<form id="form-box" class="mb-col-12 col-4 flex justify-center" method="POST">
		<div id="error-box" class="mb-col-12 col-12 flex justify-center" <?php echo $error ? '' : 'style="display: none;"'; ?>>
			<p class="error-message"><?php echo $error ?></p>
		</div>
		<div id="success-box" class="mb-col-12 col-12 flex justify-center" <?php echo $success ? '' : 'style="display: none;"'; ?>>
			<p class="success-message" <?php echo $success ? 'style="display: block;"' : ''; ?>><?php echo $success ?></p>
		</div>
		<div class="full-name mb-col-8 col-9 flex">
			<div class="mb-col-12 col-6 flex">
				<label for="first-name">Voornaam:</label>
				<input id="first-name" class="input" type="text" name="first-name" placeholder="John" required />
			</div>
			<div class="mb-col-12 col-6 flex">
				<label for="last-name">Achternaam:</label>
				<input id="last-name" class="input" type="text" name="last-name" placeholder="Doe" required />
			</div>
		</div>
		<div class="mb-col-8 col-9 flex">
			<label for="email">E-mail:</label>
			<input id="email" class="input" type="email" name="email" placeholder="johndoe@doe.com" required>
		</div>
		<div class="mb-col-8 col-9 flex">
			<label for="message">Bericht:</label>
			<textarea id="message" class="input" name="message" required></textarea>
		</div>
		<div class="send-message mb-col-6 col-6 pt-30">
			<button class="button" type="submit">Verzenden</button>
		</div>
	</form>

	<div id="contact-info-box" class="mb-col-12 col-4 flex">
		<ul class="flex justify-center py-30 px-30">
			<li class="mb-col-12 col-12 flex align-center">
				<img class="contact-img invert-color-img" src="images/iconEmail.svg" alt="E-mail">
				<span>info@connectenplay.nl</span>
			</li>
			<li class="mb-col-12 col-12 flex align-center">
				<img class="contact-img invert-color-img" src="images/iconPhone.svg" alt="Telefoonnummer">
				<span>+31 6 02233555</span>
			</li>
			<li class="mb-col-12 col-12 flex align-center">
				<img class="contact-img invert-color-img" src="images/iconLocation.svg" alt="Locatie">
				<span>Kaartmanstraat 83 5742RD Piondorp</span>
			</li>
		</ul>
	</div>
</section>
<?php require_once '/var/www/php/Shared/footer.php'; ?>