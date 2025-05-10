<?php

require_once '../php/Shared/header.php'; 
require_once '../php/Shop/Controllers/DashbordController.php';

// maak DashboardController object aan
$controller = new DashboardController();

// Haal alle buttons op uit de controller
$allButtons = $controller->getButtons();
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="Banner afbeelding" />

<section id="dashboard-container" class="flex justify-center">
    <div id="dashboardBox" class="col-6">
        <?php if (isset($user) && ($user->getRole() === UserRole::EMPLOYEE || $user->getRole() === UserRole::ADMINISTRATOR)): ?>
            <div class="employeeBox flex justify-center p-30">
                <!-- Er wordt over elke button geloopt en deze wordt op de pagina weergegeven -->
                <p class="dasboardSectionTitel col-12 pb-30">EMPLOYEE</p>
                <?php foreach($allButtons as $button) {
                    if ($button['role'] === "EMPLOYEE") {
                        echo '<a class="dashboardButton col-3 text-center" href="'.$button['url'].'">' .$button['nameButton']. '</a>';
                    }
                }?>
            </div>
        <?php endif; ?>


        <!-- Scheidingslijn tussen de rollen -->
        <hr class="lineBetweenRoles col-12"></hr>

        <?php if (isset($user) && ($user->getRole() === UserRole::ADMINISTRATOR)): ?>
            <div class="administratorBox flex justify-center p-30">
                <!-- Met alleen de rol ADMINISTRATOR wordt de sectie administrator getoond -->
                <p class="dasboardSectionTitel col-12 pb-30">ADMINISTRATOR</p>
                <?php foreach($allButtons as $button) {
                    if ($button['role'] === "ADMINISTRATOR") {
                        echo '<a class="dashboardButton col-3 text-center" href="'.$button['url'].'">' .$button['nameButton']. '</a>';
                    }
                }?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../php/Shared/footer.php'; ?>