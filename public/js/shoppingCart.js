// Wacht tot de pagina volledig geladen is
document.addEventListener("DOMContentLoaded", function () {
	const cartButton = document.getElementById("cart-button"); // Knop om de winkelwagen te openen/sluiten
	const cartDropdown = document.getElementById("cart-dropdown"); // Dropdown-menu van de winkelwagen

	// Klik op het winkelwagen-icoon: toon/verberg het dropdown-menu
	cartButton.addEventListener("click", function (e) {
		e.stopPropagation(); // Voorkom dat het click-event naar boven bubbelt
		cartDropdown.classList.toggle("hidden"); // Wissel tussen tonen/verbergen
		updateCartDropdown(); // Herlaad de inhoud van de winkelwagen
	});

	// Klik buiten de winkelwagen: sluit het dropdown-menu
	document.addEventListener("click", function (e) {
		const cartContainer = document.getElementById("cart-icon-container");
		if (!cartContainer.contains(e.target)) {
			cartDropdown.classList.add("hidden"); // Verberg winkelwagen
		}
	});

	// Inistialiseert de winkelwageninhoud bij het laden
	updateCartDropdown();
});

/**
 * Vult het winkelwagen dropdown-menu met de actuele inhoud
 * en werkt ook de cart count badge bij
 */
function updateCartDropdown() {
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]"); // Haalt winkelwagenitems op
	const cartItemsList = document.getElementById("cart-items");
	const cartCount = document.getElementById("cart-count");

	cartItemsList.innerHTML = ""; // Maakt de lijst eerst leeg

	// Checkt of de winkelwagen leeg is
	if (cartEntries.length === 0) {
		// Als de winkelwagen leeg is, toon dit
		cartItemsList.innerHTML = "<li>Je winkelwagen is leeg.</li>";
	} else {
		// Voor elke item in de winkelwagen, voeg een <li> toe
		cartEntries.forEach((entry) => {
			const li = document.createElement("li");
			li.classList.add("cart-item"); // Class voor <li> met styling
			// Inhoud van de <li>
			li.innerHTML = `
				<span class="cart-item-name">${entry.name}</span>
				<div class="cart-item-details">
					<span class="cart-item-price">€ ${entry.price}</span>
					<span class="cart-item-amount">${entry.amount} x</span>
					<button class="remove-item-button cart-icon-button" data-id="${entry.gameId}">
						<img src="/images/trash.svg" alt="Verwijder" class="invert-color-img cart-icon-image" />
					</button>
				</div>
			`;
			cartItemsList.appendChild(li); // Voegt het <li> element toe als child aan de bestaande DOM-element
		});

		// Voegt event listeners toe aan de verwijderknoppen
		cartItemsList.querySelectorAll(".remove-item-button").forEach((button) => {
			button.addEventListener("click", function (e) {
				e.stopPropagation(); // Voorkomt sluiten van het dropdown-menu bij het klikken
				const gameId = this.getAttribute("data-id");
				removeItemFromCart(gameId); // Verwijderd de item
			});
		});
	}

	// Update het totaal aantal items en toon/verberg de badge
	const totalItems = cartEntries.reduce((sum, e) => sum + e.amount, 0);
	cartCount.textContent = totalItems;
	cartCount.style.display = totalItems > 0 ? "inline-block" : "none";
}

/**
 * Verwijdert een item uit de winkelwagen (verlaagt de hoeveelheid of verwijdert het helemaal)
 * @param {number|string} gameId - Het ID van de game dat verwijderd moet worden 
 */
function removeItemFromCart(gameId) {
	gameId = parseInt(gameId, 10); // Zorgt dat gameId een integer is
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]");

	// Zoekt index van de entry met dit gameId
	const entryIndex = cartEntries.findIndex((e) => parseInt(e.gameId, 10) === gameId);

	// Als er geen entry is gevonden, doe niets
	if (entryIndex === -1) return;

	if (cartEntries[entryIndex].amount > 1) {
		// Verminder de amount met 1
		cartEntries[entryIndex].amount--;
	} else {
		// Verwijder de entry volledig uit de winkelwagen
		cartEntries.splice(entryIndex, 1);
	}

	// Sla bijgewerkte winkelwagen op
	localStorage.setItem("cartEntries", JSON.stringify(cartEntries));

	// Update het dropdown-menu
	updateCartDropdown();
}
