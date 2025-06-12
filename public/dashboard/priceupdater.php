<?php
require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shared/Guards/AdminGuard.php";

$adminGuard = new AdminGuard();
$adminGuard->redirectIfNotAllowed("/dashboard.php", 302);
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="price-update-container" class="col-6 offset-3 mb-col-12 py-30">
	<h1 class="text-center"><?= __("dashboard.priceupdater.name") ?></h1>
	<p><?= __("dashboard.priceupdater.description") ?></p>
</section>

<?php
require_once "/var/www/php/Shared/footer.php";