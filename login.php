<?php require_once './php/Shared/header.php'; ?>
<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="login-container" class="flex justify-center">
	<form id="form-box" class="mb-col-12 col-6 flex justify-center">
		<div class="mb-col-12 col-12 flex justify-center pb-30">
			<div class="mb-col-12 col-5">
				<label for="userName">Gebruikersnaam:</label>
				<input id="userName" class="input" type="text" name="userName" placeholder="JohnDoe2025" />
			</div>
		</div>

		<div class="mb-col-12 col-12 flex justify-center">
			<div class="mb-col-12 col-5">
				<label for="passwoord">Wachtwoord:</label>
				<input id="passwoord" class="input" type="password" name="password" placeholder="Wachtwoord" />
			</div>
		</div>
		<div class="send-message mb-col-6 col-5 pt-30">
			<button class="button" type="submit">Verzenden</button>
		</div>
	</form>
</section>

<?php require_once './php/Shared/footer.php'; ?>