// this doesn't change so therefor it's a constant variable
const diensten = [
	{
		id: 1,
		title: "Video Games",
		description:
			"Duik in de wereld van videogames, waar strategie en actie elkaar ontmoeten. Ontdek de nieuwste releases en de klassieke favorieten, van singleplayer avonturen tot multiplayer games voor alle leeftijden.",
		image: "/images/dienst_1.jpg",
	},
	{
		id: 2,
		title: "Kaartspellen",
		description:
			"Speel de beste kaartspellen en leer nieuwe spellen kennen. Van klassieke kaartspellen zoals poker en bridge tot moderne kaartspellen zoals Magic: The Gathering en Yu-Gi-Oh!",
		image: "/images/dienst_2.jpg",
	},
	{
		id: 3,
		title: "Bordspellen",
		description:
			"Verzamel je vrienden en familie voor een gezellige spelavond! Van strategische bordspellen tot snelle kaartspellen, er is voor ieder wat wils. Ontdek onze top-aanbevelingen.",
		image: "/images/dienst_3.jpg",
	},
	{
		id: 4,
		title: "Overig",
		description:
			"Test je vaardigheden in onze spannende toernooien! Doe mee met videogamecompetities of bordspelwedstrijden en bewijs dat jij de kampioen bent. Iedereen is welkom, van beginner tot expert.",
		image: "/images/dienst_4.jpg",
	},
]

const dienstenContainer = document.getElementById("diensten")

// loop through the diensten array and create an element for each dienst
for (var i = 0; i < diensten.length; i++) {
	dienstenContainer.appendChild(createDienstElement(diensten[i]))
}

// create a dienst element with the given dienst object
function createDienstElement(dienst) {
	// create a new div element
	const dienstElement = document.createElement("div")
	// add classes to the dienst element, each class is its own string
	dienstElement.classList.add(
		"dienst",
		"col-6",
		"flex",
		"flex-col",
		"justify-center",
		"mb-col-12"
	)

	// set the innerHTML of the dienst element just like .appendChild() does
	// this is a template string, it allows you to use variables in the string
	dienstElement.innerHTML = `
        <img src="${dienst.image}" alt="${dienst.title}" />
        <div class="flex px-15 pb-10">
			<h2>${dienst.title}</h2>
        	<p>${dienst.description}</p>
        	<button class="button">Bekijk</button>
		</div>
    `
	// return the dienst element
	return dienstElement
}
