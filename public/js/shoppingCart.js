// Wacht tot de pagina volledig geladen is
document.addEventListener("DOMContentLoaded", function () {
	const cartButton = document.getElementById("cart-button") // Knop om de winkelwagen te openen/sluiten
	const cartDropdown = document.getElementById("cart-dropdown") // Dropdown-menu van de winkelwagen

	// Klik op het winkelwagen-icoon: toon/verberg het dropdown-menu
	cartButton.addEventListener("click", function (e) {
		e.stopPropagation() // Voorkom dat het click-event naar boven bubbelt
		cartDropdown.classList.toggle("hidden") // Wissel tussen tonen/verbergen
		updateCartDropdown() // Herlaad de inhoud van de winkelwagen
	})

	// Klik buiten de winkelwagen: sluit het dropdown-menu
	document.addEventListener("click", function (e) {
		const cartContainer = document.getElementById("cart-icon-container")
		if (!cartContainer.contains(e.target)) {
			cartDropdown.classList.add("hidden") // Verberg winkelwagen
		}
	})

	// Inistialiseert de winkelwageninhoud bij het laden
	updateCartDropdown()
})

/**
 * Vult het winkelwagen dropdown-menu met de actuele inhoud
 * en werkt ook de cart count badge bij
 */
function updateCartDropdown() {
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]") // Haalt winkelwagenitems op
	const cartItemsList = document.getElementById("cart-items")
	const cartCount = document.getElementById("cart-count")

	cartItemsList.innerHTML = "" // Maakt de lijst eerst leeg

	// Checkt of de winkelwagen leeg is
	if (cartEntries.length === 0) {
		// Als de winkelwagen leeg is, toon dit
		cartItemsList.innerHTML = "<li>Je winkelwagen is leeg.</li>"
	} else {
		// Voor elke item in de winkelwagen, voeg een <li> toe
		cartEntries.forEach((entry) => {
			const li = document.createElement("li")
			li.classList.add("cart-item") // Class voor <li> met styling
			// Inhoud van de <li>
			li.innerHTML = `
				<span class="cart-item-name">${entry.name}</span>
				<div class="cart-item-details">
					<div class="cart-item-controls">
						${
							entry.amount > 1
								? `<button class="remove-item-button cart-button" data-id="${entry.gameId}">-</button>`
								: `<button class="remove-item-button cart-icon-button" data-id="${entry.gameId}">
								<img src="/images/trash.svg" alt="Verwijder" class="invert-color-img cart-icon-image" />
								</button>`
						}
						<span class="cart-item-amount">${entry.amount}</span>
						<button class="add-item-button cart-button" data-id="${entry.gameId}">+</button>
					</div>
					<span class="cart-item-price">${formatPrice(
						entry.price * entry.amount
					)}</span>
				</div>
			`
			cartItemsList.appendChild(li) // Voegt het <li> element toe als child aan de bestaande DOM-element
		})

		// Voegt event listeners toe aan de verwijderknoppen en de toevoegenknoppen
		cartItemsList
			.querySelectorAll(".remove-item-button")
			.forEach((button) => {
				button.addEventListener("click", function (e) {
					e.stopPropagation() // Voorkomt sluiten van het dropdown-menu bij het klikken
					const gameId = this.getAttribute("data-id")
					removeItemFromCart(gameId) // Verwijderd de item
				})
			})
		cartItemsList.querySelectorAll(".add-item-button").forEach((button) => {
			button.addEventListener("click", function (e) {
				e.stopPropagation() // Voorkomt sluiten van het dropdown-menu bij het klikken
				const gameId = this.getAttribute("data-id")
				addItemToCart(gameId) // Verwijderd de item
			})
		})
	}

	// Update het totaal aantal items en toon/verberg de badge
	const totalItems = cartEntries.reduce((sum, e) => sum + e.amount, 0)
	cartCount.textContent = totalItems
	cartCount.style.display = totalItems > 0 ? "inline-block" : "none"
	updateCartPrices(cartEntries) // Update de prijzen in de winkelwagen
}

/**
 * Verwijdert een item uit de winkelwagen (verlaagt de hoeveelheid of verwijdert het helemaal)
 * @param {number|string} gameId - Het ID van de game dat verwijderd moet worden
 */
function removeItemFromCart(gameId) {
	gameId = parseInt(gameId, 10) // Zorgt dat gameId een integer is
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]")

	// Zoekt index van de entry met dit gameId
	const entryIndex = cartEntries.findIndex(
		(e) => parseInt(e.gameId, 10) === gameId
	)

	// Als er geen entry is gevonden, doe niets
	if (entryIndex === -1) return

	if (cartEntries[entryIndex].amount > 1) {
		// Verminder de amount met 1
		cartEntries[entryIndex].amount--
	} else {
		// Verwijder de entry volledig uit de winkelwagen
		cartEntries.splice(entryIndex, 1)
	}

	// Sla bijgewerkte winkelwagen op
	localStorage.setItem("cartEntries", JSON.stringify(cartEntries))

	// Update het dropdown-menu
	updateCartDropdown()
}
/**
 * Voegt een item toe aan de winkelwagen (verhoogt de hoeveelheid)
 * @param {number|string} gameId - Het ID van de game dat toegevoegd moet worden
 */
function addItemToCart(gameId) {
	gameId = parseInt(gameId, 10) // Zorgt dat gameId een integer is
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]")

	// Zoekt index van de entry met dit gameId
	const entryIndex = cartEntries.findIndex(
		(e) => parseInt(e.gameId, 10) === gameId
	)

	// Als er geen entry is gevonden, doe niets
	if (entryIndex === -1) return

	// Verhoog de amount met 1
	cartEntries[entryIndex].amount++

	// Sla bijgewerkte winkelwagen op
	localStorage.setItem("cartEntries", JSON.stringify(cartEntries))

	// Update het dropdown-menu
	updateCartDropdown()
}
function updateCartPrices(entries) {
	const subtotalElement = document.getElementById("cart-subtotal")
	const shippingElement = document.getElementById("cart-shipping")
	const taxElement = document.getElementById("cart-tax")
	const totalElement = document.getElementById("cart-total")

	// Controleer of er entries zijn in de winkelwagen
	if (!entries || entries.length === 0) {
		// Als de winkelwagen leeg is, verberg alle prijs elementen
		subtotalElement.parentElement.style.display = "none"
		shippingElement.parentElement.style.display = "none"
		taxElement.parentElement.style.display = "none"
		totalElement.parentElement.style.display = "none"
		return
	} else {
		// Als er entries zijn, toon alle prijs elementen
		subtotalElement.parentElement.style.display = "flex"
		shippingElement.parentElement.style.display = "flex"
		taxElement.parentElement.style.display = "flex"
		totalElement.parentElement.style.display = "flex"
	}

	let subtotal = 0
	entries.forEach((entry) => {
		subtotal += entry.price * entry.amount // Vermenigvuldig prijs met hoeveelheid
	})

	subtotalElement.innerHTML = formatPrice(subtotal)
	shippingElement.innerHTML = formatPrice(calculateShipping(subtotal))
	taxElement.innerHTML = formatPrice(subtotal * 0.21)
	totalElement.innerHTML = formatPrice(
		subtotal + calculateShipping(subtotal) + subtotal * 0.21
	)
}

/**
 * Bereken de verzendkosten op basis van het subtotaal
 * @param {number} subtotal
 * @returns {6.95|0} returns `6.95` als het subtotaal onder de 50 is, anders `0` voor gratis verzending
 */
const calculateShipping = (subtotal) => (subtotal >= 50 ? 0 : 6.95)

/**
 * Formatteert een prijs naar de juiste weergave (met euro-teken, twee decimalen en komma als decimaalteken)
 * @param {number} price
 * @returns {string} - De geformatteerde prijs met euro-teken en twee decimalen
 */
const formatPrice = (price) => `<span class="cart-price-indicator">€</span> ${price.toFixed(2).replace(".", ",")}`
