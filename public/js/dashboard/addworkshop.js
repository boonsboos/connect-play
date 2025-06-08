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

	// geef de query parameters weer mee aan de client
	window.location.search = queryParams.toString();
});