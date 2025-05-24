document.addEventListener("DOMContentLoaded", function () {
	const form = document.querySelector("form")
	const errorBox = document.getElementById("error-box")

	form.addEventListener("submit", function (e) {
		const firstName = document
			.querySelector('[name="first-name"]')
			.value.trim()
		const lastName = document
			.querySelector('[name="last-name"]')
			.value.trim()
		const email = document.querySelector('[name="email"]').value.trim()
		const message = document.querySelector('[name="message"]').value.trim()

		/**
		 * Alle validatie met error afhandeling
		 */
		let errors = []

		// Voornaam validatie
		if (!firstName) errors.push("Voornaam is verplicht.")
		if (firstName.length < 2)
			errors.push("Voornaam moet minstens 2 tekens bevatten.")

		// Achternaam validatie
		if (!lastName) errors.push("Achternaam is verplicht.")
		if (lastName.length < 2)
			errors.push("Achternaam moet minstens 2 tekens bevatten.")

		// Email validatie
		if (!email.includes("@") || !email.includes("."))
			errors.push("Voer een geldig e-mailadres in.")

		// Bericht validatie
		if (!message) errors.push("Bericht is verplicht.")
		if (message.length < 10)
			errors.push("Bericht moet minstens 10 tekens bevatten.")
		if (message.length > 3000)
			errors.push("Bericht mag maximaal 3000 tekens bevatten.")

		if (errors.length > 0) {
			e.preventDefault()
			console.log(errorBox.getElementsByClassName("error-message"))
			errorBox.getElementsByClassName("error-message")[0].innerText =
				errors.join("\n")
			errorBox.getElementsByClassName("error-message")[0].style.display =
				"block"
			errorBox.style.display = "flex"
		}
	})
})
