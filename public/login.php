<?php

require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/UserController.php';

$controller = new UserController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST['email']) && isset($_POST['password'])) {
		$email = $_POST['email']; // haal email uit $_POST en maak er een variabele van.
		$password = $_POST['password'];

		$controller->login($email, $password);
	}
}

if (isset($_GET['error'])) {
	$loginError = $_GET['error'];
}

?>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="login-container" class="flex justify-center">
	<form id="form-box" class="mb-col-12 col-10 flex justify-center" method="post">
		<?php if (isset($loginError)): ?>
			<div class="mb-col-12 col-12 flex justify-center">
				<p class="error-message text-center p-15"><?php echo $loginError ?></p>
			</div>
		<?php endif; ?>
		<div class="mb-col-10 col-8 flex justify-center pb-15">
			<div class="mb-col-12 col-8">
				<label for="email"><?= __("login.email") ?>:</label>
				<input id="email" class="input" type="text" name="email" placeholder="email@example.com" />
			</div>
		</div>

		<div class="mb-col-10 col-8 flex justify-center">
			<div class="mb-col-12 col-8">
				<label for="password"><?= __("login.password") ?>:</label>
				<input id="password" class="input" type="password" name="password" placeholder="<?= __("login.password") ?>" />
			</div>
		</div>
		<div class="mb-col-10 col-8 flex justify-center">
			<div class="mb-col-12 col-8">
				<a href="/wachtwoord-vergeten.php"><?= __("login.forgot_password") ?></a>
			</div>
		</div>

		<div class="mb-col-10 col-8 flex justify-center">
			<div class="mb-col-8 col-7 pt-30">
				<button class="button" type="submit"><?= __("login.login") ?></button>
			</div>

			<div class="mb-col-8 col-7 pt-30">
				<button class="button" type="button" onclick="window.location='/registreer.php'"><?= __("login.register") ?></button>
			</div>
		</div>
	</form>


</section>

<?php require_once '../php/Shared/footer.php'; ?>