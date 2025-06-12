<?php

require_once '../php/Shared/header.php'; 
require_once '../php/Shop/DataAccess/DashboardRepository.php';

$controller = new DashboardRepository();
?>

<img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
<?php if (isset($user)): ?>
<section id="dashboard-container" class="flex justify-center">
    <div id="dashboardBox" class="col-6">
        <?php if ($user->getRole() === UserRole::EMPLOYEE || $user->getRole() === UserRole::ADMINISTRATOR): ?>
            <div class="employeeBox flex justify-center p-30">
                <!-- Er wordt over elke button geloopt en deze wordt op de pagina weergegeven -->
                <p class="dasboardSectionTitel col-12 pb-30">EMPLOYEE</p>
                <?php foreach($controller->getEmployeeButtons() as $button): ?>
					<a class="dashboardButton col-3 text-center" href="<?= $button['url'] ?>"> <?= __($button['name']) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($user->getRole() === UserRole::ADMINISTRATOR): ?>
            <!-- Scheidingslijn tussen de rollen. Alleen zichtbaar wanneer admin inlog -->
            <hr class="lineBetweenRoles col-12"></hr>
            <div class="administratorBox flex justify-center p-30">
                <!-- Met alleen de rol ADMINISTRATOR wordt de sectie administrator getoond -->
                <p class="dasboardSectionTitel col-12 pb-30">ADMINISTRATOR</p>
                <?php foreach($controller->getAdminButtons() as $button): ?>
					<a class="dashboardButton col-3 text-center" href="<?= $button['url'] ?>"> <?= __($button['name']) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once '../php/Shared/footer.php'; ?>