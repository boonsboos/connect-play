const url = "product.php" // De server endpoint waar alle cart-gerelateerde acties naartoe gaan

/**
 * Voegt een game toe aan de winkelwagen via de backend en slaat het op in localStorage
 * @param {number} gameId - ID van de game dat wordt toegevoegd
 */
async function addGameToCart(gameId) {
	const addToCartButton = document.getElementById("add-to-cart")
	const formData = new FormData()
	formData.append("action", "add")
	formData.append("gameId", gameId)

	try {
		// Stuurt een POST-verzoek naar de backend
		const response = await fetch(url, {
			method: "POST",
			body: formData,
		})

		const text = await response.text() // Haalt ruwe data op

		const data = JSON.parse(text) // Probeer te parsen als JSON

		// Checkt of de data succesvol gelopen is
		if (data.success) {
			// Haal huidige cartEntries op uit localStorage
			const entry = data.cartEntry
			const cartEntries = JSON.parse(
				localStorage.getItem(`cartEntries_${userId}`) || "[]"
			)

			const existing = cartEntries.find((e) => e.gameId === entry.gameId)

			// Als een game al bestaat, verhoog de amount; voegt ander een nieuwe entry toe
			if (existing) {
				existing.amount++
			} else {
				cartEntries.push(entry)
			}
			// Zet nieuwe cartEntry in localStorage
			localStorage.setItem(`cartEntries_${userId}`, JSON.stringify(cartEntries))

			// Update de button tekst met een checkmark
			addToCartButton.innerHTML = `<i class="check"></i> Toegevoegd!`
			addToCartButton.classList.add("added")

			setTimeout(() => {
				addToCartButton.innerHTML = `Toevoegen`
				addToCartButton.classList.remove("added")
			}, 2000) // Reset de button tekst na 2 seconden;
		} else {
			alert("Fout bij toevoegen: " + (data.message ?? "Onbekend"))
		}
	} catch (e) {
		console.error(e)
		alert("Netwerkfout bij toevoegen aan winkelwagen. 2")
	}
}
