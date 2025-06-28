<?php require_once '/var/www/php/Shared/header.php'; ?>

<img class="banner-img" src="images/bannerImg.jpg" alt="<?= __('profile.banner_alt'); ?>" />

<?php
/** @var User $user */
if (isset($user)): ?>
    <section id="profile-container" class="flex justify-center py-15">
        <div class="col-12 flex align-center flex-col">
            <h1 class="heading text-center">
                <?= __('profile.greeting'); ?> <?= explode(' ', trim($user->getName()))[0] ?>
            </h1>

            <div class="flex flex-row align-center">
                <a href="/profiel.php" class="button active"><?= __('profile.my_profile'); ?></a>
                <a href="/profiel/bestellingen.php" class="button"><?= __('profile.orders'); ?></a>
                <a href="/profiel/aanpassen.php" class="button"><?= __('profile.edit'); ?></a>
            </div>

            <div class="col-6 flex justify-center">
                <div class="col-12 flex p-30">
                    <div class="flex col-12 justify-center">
                        <table style="border-collapse: collapse;">
                            <tr>
                                <td class="p-10"><strong><?= __('profile.labels.name'); ?></strong></td>
                                <td class="p-10"><?= $user->getName() ?></td>
                            </tr>
                            <tr>
                                <td class="p-10"><strong><?= __('profile.labels.email'); ?></strong></td>
                                <td class="p-10"><?= $user->getEmail() ?></td>
                            </tr>
                            <tr>
                                <td class="p-10"><strong><?= __('profile.labels.address'); ?></strong></td>
                                <td class="p-10">
                                    <?php if (empty($user->getAddresses())): ?>
                                        <?= __('profile.no_addresses'); ?>
                                    <?php else: ?>
                                        <?php foreach ($user->getAddresses() as $address): ?>
                                            <?= $address->getStreetName() . ' ' . $address->getHouseNumber() . ', ' . $address->getPostalCode() . ' ' . $address->getCity() ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php require_once '/var/www/php/Shared/footer.php'; ?>