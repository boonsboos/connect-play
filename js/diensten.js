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

for (var i = 0; i < diensten.length; i++) {
	dienstenContainer.appendChild(createDienstElement(diensten[i]))
}

function createDienstElement(dienst) {
	const dienstElement = document.createElement("div")
	dienstElement.classList.add("dienst")

	dienstElement.innerHTML = `
        <img src="${dienst.image}" alt="${dienst.title}" />
        <h2>${dienst.title}</h2>
        <p>${dienst.description}</p>
        <button class="button">Bekijk</button>
    `

	return dienstElement
}
