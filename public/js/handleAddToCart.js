const url = "product.php" // De server endpoint waar alle cart-gerelateerde acties naartoe gaan

/**
 * Handler voor als een gebruiker op "Toevoegen" klikt bij een product
 * @param {number} gameId - ID van het spel dat moet worden toegevoegd
 */
async function handleAddToCart(gameId) {
	const storedOrder = localStorage.getItem("currentOrder")
	const addToCartButton = document.getElementById("add-to-cart")
	// Checkt of er al een order gemaakt is
	if (!storedOrder) {
		// Er is nog geen order; maakt eerst een nieuwe aan
		const createForm = new FormData()
		createForm.append("action", "create")

		try {
			// Stuurt een POST-verzoek naar de backend
			const data = await fetch(url, {
				method: "POST",
				body: createForm,
			}).then((res) => res.json())

			// Checkt of de data succesvol gelopen is & er een ordernummer is
			if (data.success && data.orderNumber) {
				// Sla de nieuwe order op in localStorage
				const newOrder = {
					orderNumber: data.orderNumber,
					userId: data.userId,
				}
				localStorage.setItem("currentOrder", JSON.stringify(newOrder))

				// Voegt daarna de game toe aan de winkelwagen
				addGameToCart(gameId, newOrder.orderNumber)
			} else {
				alert(
					"Fout bij aanmaken van winkelwagen: " +
						(data.message ?? "Onbekend")
				)
			}
		} catch (err) {
			console.error(err)
			alert("Netwerkfout bij aanmaken van order. 1")
		}
	} else {
		// Order bestaat al; parse deze en voeg game direct toe
		const order = JSON.parse(storedOrder)
		addGameToCart(gameId, order.orderNumber)
	}
}

/**
 * Voegt een game toe aan de winkelwagen via de backend en slaat het op in localStorage
 * @param {number} gameId - ID van de game dat wordt toegevoegd
 * @param {string} orderNumber - Huidige ordernummer
 * @returns
 */
async function addGameToCart(gameId, orderNumber) {
	const addToCartButton = document.getElementById("add-to-cart")
	const formData = new FormData()
	formData.append("action", "add")
	formData.append("gameId", gameId)
	formData.append("orderNumber", orderNumber)

	try {
		// Stuurt een POST-verzoek naar de backend
		const response = await fetch(url, {
			method: "POST",
			body: formData,
		})

		const text = await response.text() // Haalt ruwe data op
		let data

		try {
			data = JSON.parse(text) // Probee te parsen als JSON
		} catch (err) {
			console.error("Kon JSON niet parsen:", err)
			alert("Ongeldige serverresponse ontvangen.")
			return
		}

		// Checkt of de data succesvol gelopen is
		if (data.success) {
			// Haal huidige cartEntries op uit localStorage
			const entry = data.cartEntry
			const cartEntries = JSON.parse(
				localStorage.getItem("cartEntries") || "[]"
			)

			const existing = cartEntries.find((e) => e.gameId === entry.gameId)

			// Als een game al bestaat, verhoog de amount; voegt ander een nieuwe entry toe
			if (existing) {
				existing.amount++
			} else {
				cartEntries.push(entry)
			}
			localStorage.setItem("cartEntries", JSON.stringify(cartEntries))

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
