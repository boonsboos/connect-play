document.addEventListener("DOMContentLoaded", function () {
	const cartButton = document.getElementById("cart-button");
	const cartDropdown = document.getElementById("cart-dropdown");
	const cartItemsList = document.getElementById("cart-items");
	const cartCount = document.getElementById("cart-count");

	// Toon/verberg dropdown
	cartButton.addEventListener("click", function (e) {
		e.stopPropagation();
		cartDropdown.classList.toggle("hidden");
		updateCartDropdown();
	});

	// Sluit dropdown buiten klikken
	document.addEventListener("click", function (e) {
		const cartContainer = document.getElementById("cart-icon-container");
		if (!cartContainer.contains(e.target)) {
			cartDropdown.classList.add("hidden");
		}
	});

	updateCartDropdown();
});

function updateCartDropdown() {
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]");
	const cartItemsList = document.getElementById("cart-items");
	const cartCount = document.getElementById("cart-count");

	cartItemsList.innerHTML = "";

	if (cartEntries.length === 0) {
		cartItemsList.innerHTML = "<li>Je winkelwagen is leeg.</li>";
	} else {
		cartEntries.forEach((entry) => {
			const li = document.createElement("li");
			li.classList.add("cart-item");
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
			cartItemsList.appendChild(li);
		});

		cartItemsList.querySelectorAll(".remove-item-button").forEach((button) => {
			button.addEventListener("click", function (e) {
				e.stopPropagation();
				const gameId = this.getAttribute("data-id");
				removeItemFromCart(gameId);
			});
		});
	}

	// Update badge
	const totalItems = cartEntries.reduce((sum, e) => sum + e.amount, 0);
	cartCount.textContent = totalItems;
	cartCount.style.display = totalItems > 0 ? "inline-block" : "none";
}



function removeItemFromCart(gameId) {
	gameId = parseInt(gameId, 10); // Zorgt dat het een integer is
	const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]");

	//const entry = cartEntries.find((e) => e.gameId === gameId);
	const entryIndex = cartEntries.findIndex((e) => parseInt(e.gameId, 10) === gameId);

	//if (!entry) return;
	if (entryIndex === -1) return;

	if (cartEntries[entryIndex].amount > 1) {
		cartEntries[entryIndex].amount--;
	} else {
		// Verwijder de hele entry
		cartEntries.splice(entryIndex, 1);
	}

	localStorage.setItem("cartEntries", JSON.stringify(cartEntries));

	updateCartDropdown();
}
