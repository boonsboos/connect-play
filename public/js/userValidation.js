document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const errorBox = document.getElementById('error-box');

    form.addEventListener('submit', function (e) {
        const firstname = document.querySelector('[name="firstname"]').value.trim();
        const lastname = document.querySelector('[name="lastname"]').value.trim();

        const email = document.querySelector('[name="email"]').value.trim();
        const repeatEmail = document.querySelector('[name="repeat_email"]').value.trim();

        const password = document.querySelector('[name="password"]').value;
        const repeatPassword = document.querySelector('[name="repeat_password"]').value;

        const streetname = document.querySelector('[name="streetname"]').value.trim();
        const postalcode = document.querySelector('[name="postalcode"]').value.replace(/\s+/g, '').toUpperCase();
        const housenumber = document.querySelector('[name="housenumber"]').value.trim();
        const city = document.querySelector('[name="city"]').value.trim();

        /**
         * Alle validatie met error afhandeling
         */
        let errors = [];

        // Naam validatie
        if (!firstname) errors.push("Voornaam is verplicht.");
        if (!lastname) errors.push("Achternaam is verplicht.");

        // Email validatie
        if (!email.includes('@') || !email.includes('.')) errors.push("Voer een geldig e-mailadres in.");
        if (email !== repeatEmail) errors.push("E-mailadressen komen niet overeen.");

        // Wachtwoord validatie
        if (password.length < 8) errors.push("Wachtwoord moet minstens 8 tekens bevatten.");
        if (password !== repeatPassword) errors.push("Wachtwoorden komen niet overeen.");

        // Adres validatie
        if (!streetname) errors.push("Straatnaam is verplicht.");

        if (!postalcode.match(/^\d{4}[A-Z]{2}$/)) errors.push("Voer een geldige Nederlandse postcode in (bijv. 1234AB).");
        if (!housenumber) errors.push("Huisnummer is verplicht.");
        if (!city) errors.push("Plaats is verplicht.");

        if (errors.length > 0) {
            e.preventDefault();
            errorBox.innerText = errors.join("\n");
            errorBox.style.display = 'block';
        }
    });
});

