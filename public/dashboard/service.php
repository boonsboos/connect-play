<?php
require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shared/Guards/EmployeeGuard.php";

// check of de gebruiker toestemming heeft
$employeeGuard = new EmployeeGuard();
$employeeGuard->redirectIfNotAllowed();


require_once "/var/www/php/Profile/Domain/Contact.php";
require_once "/var/www/php/Profile/Domain/ContactReplyStatus.php";

require_once "/var/www/php/Shop/Controllers/Dashboard/ServiceController.php";

$controller = new ServiceController();

// de ID van de opgeloste contactpoging
if (isset($_GET["resolved"]) && is_numeric($_GET["resolved"])) {
	$controller->markInquiryAsResolved($_GET["resolved"]);
}

// de ID van de beantwoorde contactpoging
if (isset($_GET["answered"]) && is_numeric($_GET["answered"])) {
	$controller->markInquiryAsAnswered($_GET["answered"]);
}
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
<h1 class="text-center">ServiceDesk&trade;</h1>

<section id="service-container" class="flex flex-row">
	<div class="col-6 offset-3 pb-15">
		<?php if (!empty($controller->getServiceInquiries())): ?>
			<hr>
			<?php foreach ($controller->getServiceInquiries() as $contact): ?>
				<details class="col-12 py-10">
					<summary><?php echo $contact->getFirstName() . ' ' . $contact->getLastName() ?> | <b><?php echo $contact->getStatus()->asString() ?></b></summary>
					<p><b>Van: <?php echo $contact->getEmail() ?></b></p>
					<p><b>Op: <?php echo $contact->getCreatedAt() ?></b></p>
					<p><b>Contactpoging ID:<?php echo $contact->getId() ?></b></p>
					<p><?php echo $contact->getMessage() ?></p>
					<div class="flex">
						<div class="col-4 py-30">
							<?php if ($contact->getStatus() == ContactReplyStatus::Unread): ?>
								<a class="button" href="?answered=<?php echo $contact->getId() ?>">Beantwoorden</a>
							<?php elseif ($contact->getStatus() == ContactReplyStatus::Answered): ?>
								<a class="button" href="?resolved=<?php echo $contact->getId() ?>">Markeer als opgelost</a>
							<?php endif; ?>
						</div>
					</div>
				</details>
				<hr>
			<?php endforeach;
		else: ?>
			<div class="py-30">
				<h4 class="success-message text-center" style="display: block;">Geen openstaande contactpogingen. :)</h4>
			</div>
		<?php endif; ?>
	</div>
</section>


<?php
require_once "/var/www/php/Shared/footer.php";
