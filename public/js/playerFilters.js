// haal input op
let maxPlayers = document.getElementById("maxspelers");
// haal label op
let maxPlayersLabel = document.getElementById("maxspelers-value");

// ken de initiele waarde toe
maxPlayersLabel.innerText = maxPlayers.value;

// luister voor veranderingen
maxPlayers.addEventListener("change", (event) => {
	maxPlayersLabel.innerText = event.target.value;
});

// hetzelfde voor min. spelers
let minPlayers = document.getElementById("minspelers");
let minPlayersLabel = document.getElementById("minspelers-value");

minPlayersLabel.innerText = minPlayers.value;

minPlayers.addEventListener("change", (event) => {
	minPlayersLabel.innerText = event.target.value;
});