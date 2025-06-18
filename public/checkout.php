<?php
require_once '/var/www/php/Shop/Controllers/ShoppingCartController.php';
require_once '/var/www/php/Shared/header.php';

$shoppingCartController = new ShoppingCartController();
$checkoutOrder = $shoppingCartController->getCheckoutOrder();

// als checkoutOrder leeg is rederect naar de webshop.php
if(empty($checkoutOrder)) {
	header("Location: /webshop.php", true, 303);
	die();
}
debug($checkoutOrder);
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="checkout-container">
	<div class="checkoutBox">
		<div class="orderBox">
			<h2>Bestelling van: <?php echo htmlspecialchars($checkoutOrder["username"]); ?> </h2>
			<h3>Orderunmmer: <?php echo htmlspecialchars($checkoutOrder["orderId"]); ?> </h3>
		</div>
		<div class="checkoutEntryBox">
			<h3>Artikelen</h3>
			<?php foreach($checkoutOrder["cartEntries"] AS $CartEntry):?>
				<p>Game naam: <?= htmlspecialchars($CartEntry->getGame()->getName()); ?> </p>
				<p>Aantal: <?= htmlspecialchars($CartEntry->getAmount()); ?> </p>
				<p>Prijs per artikel: € <?= htmlspecialchars($CartEntry->getPriceSnapshot()); ?> </p>
			<?php endforeach; ?>
		</div>
		<div class="totalPriceBox">
			<p class="checkout-price">Subtotaal: <span id="checkout-subtotal">€0,00</span></p>
			<p class="checkout-price">Verzendkosten: <span id="checkout-shipping">€0,00</span></p>
			<p class="checkout-price">BTW (21%): <span id="checkout-tax">€0,00</span></p>
			<p class="checkout-price">Totaal: <span id="checkout-total">€0,00</span></p>
		</div>
		<div class="addressBox">
			<h3>Adres</h3>
			<?php foreach($checkoutOrder["address"] AS $addressRow):?>
				<p>Straatnaam: <?= htmlspecialchars($addressRow->getStreetName());
				var_dump($addressRow) ?> </p>
			<?php endforeach; ?>
		</div>
		<div class="bankButton">
				
		</div>
	</div>
</section>

<script src="/js/calculateCheckout.js"></script>

<?php require_once '/var/www/php/Shared/footer.php'; ?>