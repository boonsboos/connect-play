<?php require_once '/var/www/php/Shared/header.php'; ?>
<!-- Background banner with title, text and button -->
<section class="home-banner flex justify-center align-center">
    <div class="mb-col-12 col-9 flex justify-center">
        <div class="home-head-box py-50 col-6 mb-col-12 flex justify-center">
            <h1 class="heading pb-15">
                <?= __('services.title'); ?>
            </h1>
            <p class="text-center pb-10 col-12 px-10">
                <?= __('services.text'); ?>
            </p>
            <div class="pt-10">
                <a class="button p-10" href="contact.php">
                    <?= __('services.cta'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="flex justify-center py-15">
    <div id="dienstDetails" class="flex flex-col justify-center col-6 mb-col-8 px-30">
        <!-- Here will the 'dienst' be placed -->
    </div>
</section>

<script src="js/diensten.js"></script>
<script>
    dienstDetails();
</script>
<?php require_once '/var/www/php/Shared/footer.php'; ?>