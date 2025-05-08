<?php

require_once '../php/Shared/header.php';

?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="register-container" class="flex justify-center">
    <form id="form-box" class="mb-col-12 col-6 flex justify-center" method="post" action="register.php">
        <div class="col-9">
            <label>Voornaam</label>
            <input type="text" name="firstname" value="" required />
        </div>

        <div class="col-3">
            <label>Tussenvoegsel</label>
            <input type="text" name="infix" value="" required />
        </div>

        <div class="col-12">
            <label>Achternaam</label>
            <input type="text" name="lastname" value="" required />
        </div>

        <div class="col-12">
            <label>Email</label>
            <input type="email" name="email" value="" placeholder="email@example.com" required />
        </div>

        <div class="col-12">
            <label>Herhaal Email</label>
            <input type="email" name="email" value="" required />
        </div>

        <div class="col-12">
            <label>Wachtwoord</label>
            <input type="password" name="password" required />
        </div>

        <div class="col-12">
            <label>Herhaal wachtwoord</label>
            <input type="password" name="password" required />
        </div>

        <div class="col-12">
            <label>Adres</label>
            <input type="text" name="address" required />
        </div>

        <div class="col-6">
            <label>Postcode</label>
            <input type="text" name="postalcode" value="" required />
        </div>

        <div class="col-6">
            <label>Huisnummer</label>
            <input type="text" name="housenumber" value="" required />
        </div>

        <div class="col-12">
            <label>Plaats</label>
            <input type="text" name="city" value="" required />
        </div>

        <button type="submit">Registreer</button>
    </form>
</section>

<?php require_once '../php/Shared/footer.php'; ?>