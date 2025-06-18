document.addEventListener("DOMContentLoaded", calculateTotalOrder)

function calculateTotalOrder() {
	const subtotalElement = document.getElementById("checkout-subtotal")
	const shippingElement = document.getElementById("checkout-shipping")
	const taxElement = document.getElementById("checkout-tax")
	const totalElement = document.getElementById("checkout-total")
	const hiddenTotalElement = document.getElementById("hidden-checkout-total")

	const cartEntries = JSON.parse(localStorage.getItem(`currentOrder`))

	if (!cartEntries || !cartEntries.cartEntries) {
		return
	}

	let subtotal = 0

	cartEntries.cartEntries.forEach((entry) => {
		priceGame = entry.price
		amountGame = entry.amount

		subtotal += priceGame * amountGame
	})

	subtotalElement.innerHTML = formatPrice(subtotal)
	shippingElement.innerHTML = formatPrice(calculateShipping(subtotal))
	taxElement.innerHTML = formatPrice(subtotal * 0.21)
	totalElement.innerHTML = formatPrice(
		subtotal + calculateShipping(subtotal) + subtotal * 0.21
	)
	hiddenTotalElement.value =
		subtotal + calculateShipping(subtotal) + subtotal * 0.21
}
