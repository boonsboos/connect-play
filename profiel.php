<?php require_once './php/Shared/header.php'; ?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="contact-container" class="flex justify-center">

    <?php
    /**
     * @var User $user
     */
    if (isset($user)):
    ?>

        <div class="col-12 flex justify-center">
            <div id="form-box" class="col-6 flex justify-center flex-row">

                <h1 class="heading pb-15">Profiel</h1>

                <div class="flex col-6 justify-center flex-col">
                    <p class="text-center">Username: <?php echo $user->getName() ?></p>
                    <p class="text-center">Email: <?php echo $user->getEmail() ?></p>
                    <p class="text-center">Role: <?php echo $user->getRole()->value ?></p>
                    <ul>
                        <?php
                        foreach ($user->getAddresses() as $address) {
                            echo "<li>" . $address->getAddress() . " " . $address->getPostalCode() . ", " . $address->getCity() . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>
<?php require_once './php/Shared/footer.php'; ?>