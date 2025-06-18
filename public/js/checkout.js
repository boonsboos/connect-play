document
	.getElementById("checkout-button")
	.addEventListener("click", createOrder)

async function createOrder() {
	// maak een leeg pakketje die je meestuur naar de server (POST)
	const orderData = new FormData()

	// vul het pakketje met gegevens die je mee wilt sturen
	orderData.append("action", "createOrder")

	try {
		// stuur het pakketje(orderData) naar /checkout-action.php
		// orderResponse is een respone-object
		const orderResponse = await fetch("/checkout-action.php", {
			method: "POST",
			body: orderData,
		})

		const orderDataResponse = await orderResponse.json()

		if (orderDataResponse.success) {
			// haal het ordernummer en userId op
			const orderNumber = orderDataResponse.orderNumber
			const userId = orderDataResponse.userId

			// in de localstorage kunnen meerdere gebruikers entries hebben staan daarom wordt de userId meegegeven om alleen diegene op te halen
			const cartEntriesKey = `cartEntries_${userId}` // de key is de naam waarop je iets opslaat of ophaalt in de localstorage
			const cartEntriesJson = localStorage.getItem(cartEntriesKey) // een JSON-string een tekstversie van een array of object

			// maak een nieuw pakketje aan voor de cartentries en voeg drie dingen toe
			const cartEntryData = new FormData()
			cartEntryData.append("action", "addCartEntries")
			cartEntryData.append("orderNumber", orderNumber)
			cartEntryData.append("cartEntries", cartEntriesJson)

			const cartResponse = await fetch("/checkout-action.php", {
				method: "POST",
				body: cartEntryData,
			})

			const cartEntryDataResponse = await cartResponse.json()

			if (!cartEntryDataResponse.success) {
				alert("Toevoegen van winkelwagen aan order is niet gelukt.")
				return
			}

			// sla de informatie op in de localstorage (voor later)
			localStorage.setItem(
				"currentOrder",
				JSON.stringify({
					orderNumber,
					userId,
					cartEntries: JSON.parse(cartEntriesJson),
				})
			)
			window.location.href = "/checkout.php"
		} else {
			// de server heeft deze foutmelding teruggestuurd
			alert(orderDataResponse.message)
		}
	} catch (e) {
		console.log(e)
		//  deze error komt als er iets misgaat zoals netwerkfout
		alert("Afrekenen niet mogelijk, probeer het later nog een keer!")
	}
}
