let gameIdSelection = document.getElementById("gameId");
let queryParams = new URLSearchParams(window.location.search);

// als we de parameter al hebben, zet die op de select.
if (queryParams.has("gameId")) {
	gameIdSelection.value = queryParams.get("gameId");
}

gameIdSelection.addEventListener("change", (event) => {
	let gameId = event.target.value;

	if (gameId === "") {
		queryParams.delete("gameId");
	} else {
		queryParams.set("gameId", gameId);
	}

	// de status query param is alleen maar belangrijk de eerste keer dat de pagina laadt
	queryParams.delete("status");

	// geef de query parameters weer mee aan de client
	window.location.search = queryParams.toString();
});