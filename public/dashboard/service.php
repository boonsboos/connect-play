<?php
require_once "/var/www/php/Shared/Guards/EmployeeGuard.php";

// check of de gebruiker toestemming heeft
//$employeeGuard = new EmployeeGuard();
//$employeeGuard->redirectIfNotAllowed();

require_once "/var/www/php/Shared/header.php";

require_once "/var/www/php/Profile/Domain/Contact.php";
require_once "/var/www/php/Profile/Domain/ContactReplyStatus.php";

require_once "/var/www/php/Shop/Controllers/Dashboard/ServiceController.php";

$controller = new ServiceController();

if (isset($_GET["resolved"]))
	$controller->resolveTicket($_GET["resolved"]);
?>

    <img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
    <h1 class="text-center">ServiceDesk&trade;</h1>

    <section id="service-container" class="flex flex-row">
		<div class="col-6 offset-3 pb-15">
			<hr>
			<?php foreach ($controller->getServiceInquiries() as $contact): ?>
				<details class="col-12 py-10">
					<summary> <?php echo $contact->getFirstName() . ' ' . $contact->getLastName() ?> | <?php echo $contact->getStatus()->asString()?></summary>
                    <p>
						<b>From: <?php echo $contact->getEmail() ?></b>
					</p>
					<p>
						<b>At: <?php echo $contact->getCreatedAt() ?></b>
					</p>
					<p>
						<b>Ticket ID:<?php echo $contact->getId() ?></b>
					</p>
					<p>
						<?php echo $contact->getMessage() ?>
					</p>
					<div class="flex">
						<div class="col-2 py-30">
							<?php if ($contact->getStatus() == ContactReplyStatus::Unread):?>
								<a class="button" href="?answer=<?php echo $contact->getEmail()?>">Beantwoorden</a>
        					<?php elseif ($contact->getStatus() == ContactReplyStatus::Answered):?>
								<a class="button" href="?resolved=<?php echo $contact->getId() ?>">Markeer als opgelost</a>
							<?php endif; ?>
						</div>
					</div>
				</details>
			<hr>
			<?php endforeach; ?>
		</div>
    </section>


<?php
require_once "/var/www/php/Shared/footer.php";
