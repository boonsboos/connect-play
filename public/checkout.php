<?php
require_once '/var/www/php/Shop/Controllers/ShoppingCartController.php';
require_once '/var/www/php/Shared/header.php';

$shoppingCartController = new ShoppingCartController();
$checkoutOrder = $shoppingCartController->getCheckoutOrder();

// als checkoutOrder leeg is rederect naar de webshop.php
if (empty($checkoutOrder) && !isset($_GET["error"])) {
	header("Location: /checkout.php?error=" . urlencode(__("checkout.error.no_items")), true, 308);
	die();
}
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="<?= __('checkout.banner_alt') ?>" />

<section id="checkout-container" class="col-12 mb-col-12 justify-center flex py-30">
	<form action="/payment.php" method="post" class="checkout-box flex flex-col col-4">
		<div class="box order-box p-15">
			<h2><?= __('checkout.title') ?> <?= htmlspecialchars($checkoutOrder["username"]); ?></h2>
			<h3><?= __('checkout.order_number') ?> #<?= htmlspecialchars($checkoutOrder["orderId"]); ?></h3>
		</div>
		<div class="box checkout-entry-box flex flex-col gap-2 p-15">
			<h3><?= __('checkout.articles') ?></h3>
			<?php if (!isset($_GET["error"])): ?>
				<div>
					<?php foreach ($checkoutOrder["cartEntries"] as $cartEntry): ?>
						<p><?= htmlspecialchars($cartEntry->getGame()->getName()); ?> - € <?= htmlspecialchars($cartEntry->getPriceSnapshot()); ?> <?= $cartEntry->getAmount() > 1 ? htmlspecialchars('* ' . $cartEntry->getAmount()) : ''; ?></p>
					<?php endforeach; ?>
				</div>
				<hr>
				<div>
					<p class="checkout-price"><?= __('checkout.subtotal') ?> <span id="checkout-subtotal">€0,00</span></p>
					<p class="checkout-price"><?= __('checkout.shipping') ?> <span id="checkout-shipping">€0,00</span></p>
					<p class="checkout-price"><?= __('checkout.tax') ?> <span id="checkout-tax">€0,00</span></p>
					<p class="checkout-price"><?= __('checkout.total') ?> <span id="checkout-total">€0,00</span></p>
				</div>
			<?php else: ?>
				<p class="error-message" style="display: block;"><?= htmlspecialchars($_GET["error"]) ?></p>
			<?php endif; ?>
		</div>
		<div class="box address-box p-15">
			<h3><?= __('checkout.address.title') ?></h3>
			<?php if ($checkoutOrder["address"]): ?>
				<p><?= htmlspecialchars($checkoutOrder["address"]->getStreetName()); ?> <?= htmlspecialchars($checkoutOrder["address"]->getHouseNumber()); ?></p>
				<p><?= htmlspecialchars($checkoutOrder["address"]->getPostalCode()); ?> <?= htmlspecialchars($checkoutOrder["address"]->getCity()); ?></p>
			<?php endif; ?>
		</div>
		<div class="box bank-button p-15">
			<h3><?= __('checkout.choose_bank') ?></h3>
			<div class="flex flex-col">
				<div>
					<input type="radio" name="bank" id="abn" value="abn_ambro" required>
					<label for="abn"><?= __('checkout.banks.abn') ?></label>
				</div>
				<div>
					<input type="radio" name="bank" id="ing" value="ing" required>
					<label for="ing"><?= __('checkout.banks.ing') ?></label>
				</div>
				<div>
					<input type="radio" name="bank" id="rabo" value="rabobank" required>
					<label for="rabo"><?= __('checkout.banks.rabo') ?></label>
				</div>
			</div>
		</div>
		<input type="hidden" id="hidden-checkout-total" name="total" value="">
		<div class="flex justify-center">
			<button type="submit" class="button"><?= __('checkout.cta') ?></button>
		</div>
	</form>
</section>

<script src="/js/calculateCheckout.js"></script>

<?php require_once '/var/www/php/Shared/footer.php'; ?>
