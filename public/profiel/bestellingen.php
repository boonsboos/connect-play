<?php
require_once '/var/www/php/Shared/header.php';
require_once '/var/www/php/Profile/Controllers/UserOrderController.php';

$userOrderController = new UserOrderController();
$error = $_GET['error'] ?? null;

/** @var User $user */
if (!isset($user)) {
    return;
}

function showErrorBox(string $message): void
{
    echo <<<HTML
    <div id="error-box" class="mb-col-12 col-12 flex justify-center pt-10">
        <p class="error-message text-center p-10">{$message}</p>
    </div>
    HTML;
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

        <div class="col-6 flex justify-center mt-20">
            <?php if (isset($_GET['orderId'])): ?>
                <?php
                $orderId = $_GET['orderId'];
                $order = $userOrderController->getUserOrderById($orderId);
                if (!$order) {
                    showErrorBox("Bestelling niet gevonden.");
                } else {
                ?>
                    <div class="order-details flex mb-col-12 col-12 flex-col align-center text-center">
                        <div class="order-summary col-6">
                            <h2>Bestelling Details</h2>

                            <div class="flex col-12 justify-center">
                                <p class="flex justify-between col-12">
                                    <span>Bestelling ID:</span>
                                    <span><?= htmlspecialchars($order->getId()) ?></span>
                                </p>
                                <p class="flex justify-between col-12">
                                    <span>Datum:</span>
                                    <span><?= htmlspecialchars($order->getDate()) ?></span>
                                </p>
                                <p class="flex justify-between col-12">
                                    <span>Status:</span>
                                    <span><?= htmlspecialchars($order->getStatus()->asString()) ?></span>
                                </p>
                                <p class="flex justify-between col-12">
                                    <span>Totaalbedrag:</span>
                                    <span>&euro; <?= number_format($order->getTotal(), 2, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>


                    <h3>Producten</h3>
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Game</th>
                                <th>Aantal</th>
                                <th>Prijs per stuk</th>
                                <th>Subtotaal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order->getEntries() as $entry): ?>
                                <tr>
                                    <td><?= htmlspecialchars($entry->getGame()->getName()) ?></td>
                                    <td><?= htmlspecialchars($entry->getCopies()) ?></td>
                                    <td>&euro; <?= number_format($entry->getPriceSnapshot(), 2, ',', '.') ?></td>
                                    <td>&euro; <?= number_format($entry->getPriceSnapshot() * $entry->getCopies(), 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="pt-30">
                        <a href="/profiel/bestellingen.php" class="button">Terug naar bestellingen</a>
                    </div>
        </div>
    <?php } ?>

<?php else: ?>
    <?php
                $orders = $userOrderController->getUserOrders();
                if (empty($orders)) {
                    showErrorBox("Geen bestellingen gevonden.");
                } else {
    ?>
        <table class="order-table w-full">
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
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order->getId()) ?></td>
                        <td><?= htmlspecialchars($order->getDate()) ?></td>
                        <td><?= htmlspecialchars($order->getStatus()->asString()) ?></td>
                        <td>&euro; <?= number_format($order->getTotal(), 2, ',', '.') ?></td>
                        <td>
                            <a href="/profiel/bestellingen.php?orderId=<?= urlencode($order->getId()) ?>">Bekijk details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
<?php endif; ?>
    </div>
    </div>
</section>

<?php require_once '/var/www/php/Shared/footer.php'; ?>