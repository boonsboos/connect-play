const url = "product.php";

async function handleAddToCart(gameId) {
	const storedOrder = localStorage.getItem("currentOrder");

	if (!storedOrder) {
		// Eerst de order aanmaken
		const createForm = new FormData();
		createForm.append("action", "create");

		try {
			const data = await fetch(url, {
				method: "POST",
				body: createForm,
			}).then((res) => res.json())
			if (data.success && data.orderNumber) {
				// Order opslaan in localStorage
				const newOrder = {
					orderNumber: data.orderNumber,
					userId: data.userId,
				}
				localStorage.setItem("currentOrder", JSON.stringify(newOrder));

				// Daarna opnieuw: game toevoegen
				addGameToCart(gameId, newOrder.orderNumber);
			} else {
				alert(
					"Fout bij aanmaken van winkelwagen: " +
						(data.message ?? "Onbekend")
				);
			}
		} catch (err) {
			console.error(err);
			alert("Netwerkfout bij aanmaken van order. 1");
		}
	} else {
		const order = JSON.parse(storedOrder);
		addGameToCart(gameId, order.orderNumber);
	}
}

async function addGameToCart(gameId, orderNumber) {
	const formData = new FormData();
	formData.append("action", "add");
	formData.append("gameId", gameId);
	formData.append("orderNumber", orderNumber);

	try {
		const response = await fetch(url, {
		method: "POST",
		body: formData
	});

	const text = await response.text();
	let data;

	try {
		
		data = JSON.parse(text);
	} catch (err) {
		console.error("Kon JSON niet parsen:", err)
		alert("Ongeldige serverresponse ontvangen.")
		return;
	}

	if (data.success) {
		const entry = data.cartEntry;
		const cartEntries = JSON.parse(
			localStorage.getItem("cartEntries") || "[]"
		);

		const existing = cartEntries.find((e) => e.gameId === entry.gameId);

		if (existing) {
			existing.amount++
		} else {
			cartEntries.push(entry);
		}
		localStorage.setItem("cartEntries", JSON.stringify(cartEntries));
		alert("Game toegevoegd aan winkelwagen!");
	} else {
		alert("Fout bij toevoegen: " + (data.message ?? "Onbekend"));
	}
	} catch (e) {
		console.error(e);
		alert("Netwerkfout bij toevoegen aan winkelwagen. 2");
	}
}
