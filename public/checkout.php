<?php
require_once '/var/www/php/Shop/Controllers/ShoppingCartController.php';
require_once '/var/www/php/Shared/header.php';

$shoppingCartController = new ShoppingCartController();
$checkoutOrder = $shoppingCartController->getCheckoutOrder();

// als checkoutOrder leeg is rederect naar de webshop.php
if (empty($checkoutOrder) && !isset($_GET["error"])) {
	header("Location: /checkout.php?error=" . urlencode("Geen artikelen gevonden"), true, 308);
	die();
}
// debug($checkoutOrder);
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="checkout-container" class="col-12 mb-col-12 justify-center flex">
	<form action="/payment.php" method="post" class="checkout-box flex flex-col col-4">
		<div class="box order-box">
			<h2>Bestelling van: <?php echo htmlspecialchars($checkoutOrder["username"]); ?> </h2>
			<h3>Ordernummer: #<?php echo htmlspecialchars($checkoutOrder["orderId"]); ?> </h3>
		</div>
		<div class="box checkout-entry-box flex flex-col gap-2">
			<h3>Artikelen</h3>
			<?php if (!isset($_GET["error"])): ?>
				<div>
					<?php foreach ($checkoutOrder["cartEntries"] as $cartEntry): ?>
						<p><?= htmlspecialchars($cartEntry->getGame()->getName()); ?> - € <?= htmlspecialchars($cartEntry->getPriceSnapshot()); ?> <?php echo $cartEntry->getAmount() > 1 ? htmlspecialchars('* ' . $cartEntry->getAmount()) : ''; ?> </p>
					<?php endforeach; ?>
				</div>
				<hr>
				<div>
					<p class="checkout-price">Subtotaal: <span id="checkout-subtotal">€0,00</span></p>
					<p class="checkout-price">Verzendkosten: <span id="checkout-shipping">€0,00</span></p>
					<p class="checkout-price">BTW (21%): <span id="checkout-tax">€0,00</span></p>
					<p class="checkout-price">Totaal: <span id="checkout-total">€0,00</span></p>
				</div>
			<?php else: ?>
				<p class="error-message" style="display: block;"><?= htmlspecialchars($_GET["error"]) ?></p>
			<?php endif; ?>
		</div>
		<div class="box address-box">
			<h3>Afleveradres</h3>
			<?php if ($checkoutOrder["address"]): ?>
				<p><?= htmlspecialchars($checkoutOrder["address"]->getStreetName()); ?> <?= htmlspecialchars($checkoutOrder["address"]->getHouseNumber()); ?></p>
				<p><?= htmlspecialchars($checkoutOrder["address"]->getPostalCode()); ?> <?= htmlspecialchars($checkoutOrder["address"]->getCity()); ?></p>
			<?php endif; ?>
		</div>
		<div class="box bank-button">
			<h3>Kies uw bank</h3>
			<div class="flex flex-col">
				<div>
					<input type="radio" name="bank" id="abn" value="abn_ambro" required>
					<label for="abn">ABN AMRO</label>
				</div>
				<div>
					<input type="radio" name="bank" id="ing" value="ing" required>
					<label for="ing">ING</label>
				</div>
				<div>
					<input type="radio" name="bank" id="rabo" value="rabobank" required>
					<label for="rabo">Rabobank</label>
				</div>
			</div>
		</div>
		<input type="hidden" id="hidden-checkout-total" name="total" value="">
		<button type="submit" class="button">Ga verder naar betalen</button>
	</form>

</section>

<script src="/js/calculateCheckout.js"></script>

<?php require_once '/var/www/php/Shared/footer.php'; ?>