document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const errorBox = document.getElementById('error-box');

    form.addEventListener('submit', function (e) {
        const name = document.querySelector('[name="name"]').value.trim();

        const email = document.querySelector('[name="email"]').value.trim();

        const password = document.querySelector('[name="password"]').value;
        const repeatPassword = document.querySelector('[name="password-repeat"]').value;

        const streetname = document.querySelector('[name="street-name"]').value.trim();
        const postalcode = document.querySelector('[name="postal-code"]').value.replace(/\s+/g, '').toUpperCase();
        const housenumber = document.querySelector('[name="house-number"]').value.trim();
        const city = document.querySelector('[name="city"]').value.trim();

        /**
         * Alle validatie met error afhandeling
         */
        let errors = [];

        // Naam validatie
        if (!name) errors.push("Naam is verplicht.");

        // Email validatie
        if (!email.includes('@') || !email.includes('.')) errors.push("Voer een geldig e-mailadres in.");

        // Wachtwoord validatie
        if (password && password.length < 8) errors.push("Wachtwoord moet minstens 8 tekens bevatten.");
        if (password && password !== repeatPassword) errors.push("Wachtwoorden komen niet overeen.");

        // Adres validatie
        if (!streetname) errors.push("Straatnaam is verplicht.");

        if (!postalcode.match(/^\d{4}[A-Z]{2}$/)) errors.push("Voer een geldige Nederlandse postcode in (bijv. 1234AB).");
        if (!housenumber) errors.push("Huisnummer is verplicht.");
        if (!city) errors.push("Plaats is verplicht.");

        if (errors.length > 0) {
            console.log(errors);
            e.preventDefault();
            errorBox.getElementsByClassName('error-message')[0].innerText = errors.join("\n");
            errorBox.style.display = 'flex';
        }
    });
});