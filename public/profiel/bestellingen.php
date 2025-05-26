<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/UserOrderController.php';

$userOrderController = new UserOrderController();
$error = null;
/** @var User $user */
if (isset($user)):
    $order = $userOrderController->getUserOrders();
    if (empty($order)) {
        $error = "Geen bestellingen gevonden.";
    }
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
    }
?>
    <img class="banner-img" src="/images/bannerImg.jpg" alt="Banner afbeelding" />
    <section id="profile-container" class="flex justify-center py-30">
        <div class="col-12 flex align-center flex-col">
            <h1 class="heading text-center">Profiel - Bestellingen</h1>
            <div class="flex gap-20 flex-row align-center">
                <a href="/profiel.php" class="button">Mijn profiel</a>
                <a href="/profiel/bestellingen.php" class="button active">Bestellingen</a>
                <a href="/profiel/facturen.php" class="button">Facturen</a>
                <a href="/profiel/aanpassen.php" class="button">Profiel aanpassen</a>
            </div>
            <div class="col-6 flex justify-center">
                <?php if ($error): ?>
                    <div id="error-box" class="mb-col-12 col-12 flex justify-center pt-10">
                        <p class="error-message text-center p-10"><?= $error ?></p>
                    </div>
                <?php else: ?>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Bestelling ID</th>
                                <th>Datum</th>
                                <th>Status</th>
                                <th>Totaalbedrag</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order as $order): ?>
                                <tr>
                                    <td><?= $order->getId() ?></td>
                                    <td><?= $order->getDate() ?></td>
                                    <td><?= $order->getStatus()->asString() ?></td>
                                    <td>&euro; <?= number_format($order->getTotal(), 2, ',', '.') ?></td>
                                    <td>
                                        <a href="/profiel/bestelling.php?orderId=<?= $order->getId() ?>">Bekijk details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif;
require_once '/var/www/php/Shared/footer.php'; ?>