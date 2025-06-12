document
	.getElementById("checkout-button")
	.addEventListener("click", checkoutOrder)

async function checkoutOrder() {
	const currentOrder = JSON.parse(localStorage.getItem("currentOrder") ?? {})

	// maak een leeg pakketje die je meestuur naar de server (POST)
	const checkoutData = new FormData()

	// vul het pakketje met gegevens die je mee wilt sturen
	checkoutData.append("action", "setCurrentOrderNumber")

	checkoutData.append("currentOrder", currentOrder["orderNumber"])

	try {
		// stuur het pakketje(checkoutData) naar /checkout.php
		// response is een respone-object
		const response = await fetch("/checkout-action.php", {
			method: "POST",
			body: checkoutData,
		})

		const checkoutResponse = await response.json()

		if (checkoutResponse.success) {
			window.location.href = "/checkout.php"
		} else {
			// deze error komt omdat er een andere fout plaats heeft gevonden met de fetch
			alert(checkoutResponse.message)
		}
	} catch {
		// deze error komt wanneer je niet bent in gelogd
		alert("Afrekenen niet mogelijk, probeer het later nog een keer!")
	}
}
