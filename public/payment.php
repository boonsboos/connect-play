<?php
require_once '/var/www/php/Shared/header.php';

$bank = '';
$total = 00.00;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['success']) && $_POST['success'] == '1') {
    $success = true;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bank']) && isset($_POST['total']) && is_numeric($_POST['total'])) {
    switch ($_POST['bank']) {
        case 'ing':
            $bank = htmlspecialchars('ING');
            break;
        case 'abn_ambro':
            $bank = htmlspecialchars('ABN AMBRO');
            break;
        case 'rabobank':
            $bank = htmlspecialchars('Rabobank');
            break;
        default:
            $bank = __('payment.bank_label') . __('payment.bank_unknown');
    }
    $total = number_format((float)$_POST['total'], 2, ',', '.');
} else {
    header("Location: /checkout.php?error=" . urlencode(__('payment.error')), true, 308);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: /checkout.php?error=" . urlencode(__('payment.error')), true, 308);
    exit();
}
?>

<img class="banner-img" src="images/bannerImg.jpg" alt="<?= __('payment.banner_alt') ?>" />

<div id="profile-container" class="mb-flex-col flex py-50">
    <?php if ($success): ?>
        <div class="mb-col-12 col-12 flex flex-col justify-center align-center py-15">
            <h1 class="text-center pb-15"><?= __('payment.success.title') ?></h1>
            <a href="/webshop.php" class="button"><?= __('payment.success.button') ?></a>
        </div>
    <?php else: ?>
        <div class="mb-col-12 col-12 flex justify-center align-center py-10">
            <h1 class="text-center"><?= $bank ?></h1>
        </div>
        <div class="mb-col-12 col-12 flex justify-center align-center pt-10 pb-5">
            <p class="text-center">
                <?= __('payment.amount') ?>
                <?= $total ?>
            </p>
        </div>

        <form method="post" class="mb-col-12 col-12 flex justify-center align-center pb-15">
            <input type="hidden" name="success" value="1">
            <button class="button" type="submit" name="confirm_payment"><?= __('payment.pay_button') ?></button>
        </form>
    <?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const storage = localStorage.getItem('currentOrder');
        if (!storage) {
            window.location.href = '/checkout.php?error=<?= urlencode(__('payment.error')) ?>';
            return;
        }
        if (<?= $success ? 'true' : 'false'; ?> && <?= $bank == '' ? 'true' : 'false'; ?>) {
            // leeg de localStorage na succesvolle betaling
            localStorage.removeItem('currentOrder');
            localStorage.removeItem(`cartEntries_${<?= $_SESSION['userId'] ?>}`);
        }
    });
</script>

<?php require_once '/var/www/php/Shared/footer.php'; ?>
