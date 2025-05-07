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
        <div class="employeeBox flex justify-center p-30">
            <?php if (isset($user) && ($user->getRole() === UserRole::EMPLOYEE || $user->getRole() === UserRole::ADMINISTRATOR)) {
                    // Er wordt over elke button geloopt en deze wordt op de pagina weergegeven
                    echo '<p class="dasboardSectionTitel col-12 pb-30">EMPLOYEE</p>';
                    foreach($allButtons as $button) {
                        if ($button['role'] === "EMPLOYEE") {
                            echo '<a class="dashboardButton col-3 text-center" href="'.$button['url'].'">' .$button['nameButton']. '</a>';
                        }
                    }
                }
        ?>
        </div>
        <hr class="lineBetweenRoles col-12"></hr> 
        <div class="administratorBox flex justify-center p-30">
            <!-- Met alleen de rol ADMINISTRATOR wordt de sectie administrator getoond -->
            <?php if (isset($user) && $user->getRole() === UserRole::ADMINISTRATOR) {
                    echo '<p class="dasboardSectionTitel col-12 pb-30">ADMINISTRATOR</p>';
                    // Er wordt over elke button geloopt en deze wordt op de pagina weergegeven
                    foreach($allButtons as $button) {
                        if ($button['role'] === "ADMINISTRATOR") {
                            echo '<a class="dashboardButton col-3 text-center" href="'.$button['url'].'">' .$button['nameButton']. '</a>';
                        }
                    }
                }
            ?>
        </div>
    </div>
</section>

<?php require_once '../php/Shared/footer.php'; ?>