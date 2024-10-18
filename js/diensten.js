// this doesn't change so therefor it's a constant variable
const diensten = [
	{
		id: 1,
		title: "Dienst 1",
		description: "Dienst 1 beschrijving",
		image: "/images/dienst_1.webp",
	},
	{
		id: 2,
		title: "Dienst 2",
		description: "Dienst 2 beschrijving",
		image: "/images/dienst_2.jpg",
	},
	{
		id: 3,
		title: "Dienst 3",
		description: "Dienst 2 beschrijving",
		image: "/images/dienst_3.jpg",
	},
	{
		id: 4,
		title: "Dienst 4",
		description: "Dienst 2 beschrijving",
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
	dienstElement.classList.add("dienst", "col-6", "flex-col", "justify-center", "mb-col-12")

	// set the innerHTML of the dienst element just like .appendChild() does
	// this is a template string, it allows you to use variables in the string
	dienstElement.innerHTML = `
        <img src="${dienst.image}" alt="${dienst.title}" />
        <div class="flex px-15">
			<h2>${dienst.title}</h2>
        	<p>${dienst.description}</p>
        	<button class="button">Bekijk</button>
		</div>
    `
	// return the dienst element
	return dienstElement
}
