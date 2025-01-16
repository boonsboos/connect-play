<?php

require_once './php/Shared/header.php';
require_once './php/Profile/Controllers/LoginController.php';

$controller = new LoginController();
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

	<?php if (isset($loginError)): ?>
		<div class="mb-col-12 col-12 flex error-message">
			<p class="text-center"><?php echo $loginError ?></p>
		</div>
	<?php endif; ?>

	<form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post" action="login.php">
		<div class="mb-col-12 col-12 flex justify-center pb-30">
			<div class="mb-col-12 col-5">
				<label for="email">E-mail:</label>
				<input id="email" class="input" type="text" name="email" placeholder="email@example.com" />
			</div>
		</div>

		<div class="mb-col-12 col-12 flex justify-center">
			<div class="mb-col-12 col-5">
				<label for="password">Wachtwoord:</label>
				<input id="password" class="input" type="password" name="password" placeholder="Wachtwoord" />
			</div>
		</div>
		<div class="send-message mb-col-6 col-5 pt-30">
			<button class="button" type="submit">Inloggen</button>
		</div>
	</form>
</section>

<?php require_once './php/Shared/footer.php'; ?>