<?php
require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shared/Guards/EmployeeGuard.php";

$employeeGuard = new EmployeeGuard();
$employeeGuard->redirectIfNotAllowed("/dashboard.php", 302);
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="bestellingen-container py-30">
	<h1 class="text-center">Bestellingen</h1>

</section>


<?php
require_once "/var/www/php/Shared/footer.php";