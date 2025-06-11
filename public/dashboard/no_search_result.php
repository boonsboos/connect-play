<?php

require_once "/var/www/php/Shared/header.php";
require_once "/var/www/php/Shop/Controllers/WebshopController.php";
require_once "/var/www/php/Profile/Controllers/UserController.php";

$webshopController = New WebshopController();
$allSearchTerms = $webshopController->getEmptySearchResults();

?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
<h1 class="text-center">Niet gevonden artikelen</h1>

<section id="searchResults-container" class="flex justify-center col-12 py-30 br-8 mb-col-12">
    <div id="noSearchResultBox" class="flex justify-center py-40 px-30 br-8 <?php echo !empty($allSearchTerms) ? 'grid-col-3' : 'col-8 text-center';?> mb-col-9">    
        <?php if (empty($allSearchTerms)): ?>
            <h3 class="p-15">Er zijn geen mislukte zoekopdrachten beschikbaar.</h3>
        <?php else: ?>
            <?php foreach ($allSearchTerms as $searchResult): ?>
                <div id="zoekTermBox" class="p-40 br-8">
                    <h3 class="pb-10">
                        <?php echo !empty($searchResult['search_term']) ? $searchResult['search_term'] : 'Geen Titel'; ?> 
                    </h3>
                    <p class="flex justify-start">
                        <span class="label">Gebruiker:</span>
                        <span class="value"><?php echo (!empty($searchResult['name']) ? $searchResult['name'] : 'Onbekend'); ?></span>
                    </p>
                    <p class="flex justify-start">
                        <span class="label">IP-adres:</span>
                        <span class="value"><?php echo $searchResult['ip_address']; ?></span>
                    </p>
                    <p class="flex justify-start">
                        <span class="label">Datum:</span>
                        <span class="value"><?php echo $searchResult['posted_on']; ?></span>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>


<?php
require_once "/var/www/php/Shared/footer.php";