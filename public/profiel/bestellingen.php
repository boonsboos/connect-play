<?php require_once '/var/www/php/Shared/header.php'; ?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />

<?php
/** @var User $user */
if (isset($user)): ?>
    <section id="profile-container" class="flex justify-center py-15">
        <div class="col-12 flex align-center flex-col">
            <h1 class="heading text-center">Profiel - Bestellingen</h1>
            <div class="flex gap-20 flex-row align-center">
                <a href="/profiel.php" class="button">Mijn profiel</a>
                <a href="/profiel/bestellingen.php" class="button active">Bestellingen</a>
                <a href="/profiel/facturen.php" class="button">Facturen</a>
                <a href="/profiel/aanpassen.php" class="button">Profiel aanpassen</a>
            </div>
            <div class="col-6 flex justify-center">
                <!-- Hier komen de bestellingen -->
            </div>
        </div>
    </section>
<?php endif; ?>
<?php require_once '/var/www/php/Shared/footer.php'; ?>