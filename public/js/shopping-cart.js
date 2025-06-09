document.addEventListener("DOMContentLoaded", function () {
	const cartButton = document.getElementById("cart-button");
	const cartDropdown = document.getElementById("cart-dropdown");
	const cartItemsList = document.getElementById("cart-items");
	const cartCount = document.getElementById("cart-count");

    

	// Toggle dropdown tonen/verbergen
	cartButton.addEventListener("click", function (e) {
		e.stopPropagation(); // voorkomt sluiten direct bij klikken
		cartDropdown.classList.toggle("hidden");
		updateCartDropdown();
	});

	// Sluit dropdown als je ergens anders klikt
	document.addEventListener("click", function (e) {
		if (!cartDropdown.classList.contains("hidden")) {
			cartDropdown.classList.add("hidden");
		}
	});

	function updateCartDropdown() {
		const cartEntries = JSON.parse(localStorage.getItem("cartEntries") || "[]");
		cartItemsList.innerHTML = "";

		if (cartEntries.length === 0) {
			cartItemsList.innerHTML = "<li>Je winkelwagen is leeg.</li>";
		} else {
			cartEntries.forEach((entry) => {
				const li = document.createElement("li");
				li.textContent = `Game ID: ${entry.gameId} — aantal: ${entry.amount}`;
				cartItemsList.appendChild(li);
			});
		}

		// Update badge
		const totalItems = cartEntries.reduce((sum, e) => sum + e.amount, 0);
		cartCount.textContent = totalItems;
		cartCount.style.display = totalItems > 0 ? "inline-block" : "none";
	}

	// Init bij laden van pagina
	updateCartDropdown();
});

