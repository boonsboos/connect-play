<?php require_once './php/Shared/header.php'; ?>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="login-container" class="flex justify-center">
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