<?php require_once '/var/www/php/Shared/header.php'; ?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<?php
/** @var User $user */
if (isset($user)): ?>
    <section id="profile-container" class="flex justify-center py-15">
        <div class="col-12 flex align-center flex-col">
            <h1 class="heading text-center">Profiel</h1>
            <div class="flex gap-20 flex-row align-center">
                <a href="/profiel.php" class="button active">Mijn profiel</a>
                <a href="/profiel/bestellingen.php" class="button">Bestellingen</a>
                <a href="/profiel/facturen.php" class="button">Facturen</a>
                <a href="/profiel/aanpassen.php" class="button">Profiel aanpassen</a>
            </div>
            <div class="col-6 flex justify-center">
                <div class="col-6 flex flex-col gap-20 p-30 border rounded shadow-md bg-white">
                    <div class="flex flex-col gap-10">
                        <p><strong>Username:</strong> <?php echo $user->getName() ?></p>
                        <p><strong>Email:</strong> <?php echo $user->getEmail() ?></p>
                        <p><strong>Role:</strong> <?php echo $user->getRole()->value ?></p>
                    </div>
                </div>
                <div class="col-6 flex flex-col gap-20 p-30 border rounded shadow-md bg-white mt-20">
                    <p><strong>Adres</strong></p>
                    <?php if (empty($user->getAddresses())): ?>
                        <p>Geen adressen gevonden.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($user->getAddresses() as $address): ?>
                                <li>
                                    <?php echo $address->getStreet() . ' ' . $address->getHouseNumber() . ', ' . $address->getPostalCode() . ' ' . $address->getCity() ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php require_once '/var/www/php/Shared/footer.php'; ?>