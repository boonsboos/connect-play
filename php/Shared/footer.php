<!-- Footer -->
<footer class="py-30">
    <div id="footer-container" class="flex justify-center pb-15">
        <div id="footer-sections" class="col-9 flex text-center">
            <div class="contact-ons-footer mb-col-12 col-4">
                <a href="/contact.php">
                    <h3 id="contact" class="pb-10"><?= __('footer.contact') ?></h3>
                </a>
                <ul>
                    <li>
                        <p>+31 6 02233555</p>
                    </li>
                    <li>
                        <p><a href="mailto:info@connect-en-play.nl">info@connect-en-play.nl</a></p>
                    </li>
                </ul>
            </div>
            <div class="over-ons-footer mb-col-12 col-4">
                <a href="/over-ons.php">
                    <h3 id="over-ons" class="pb-10"><?= __('footer.about') ?></h3>
                </a>
                <ul>
                    <li>
                        <p>
                            Kaartmanstraat 83 <br />
                            5742RD Piondorp
                        </p>
                    </li>
                </ul>
            </div>
            <div class="maak-afspraak-footer mb-offset-3 mb-col-4 col-4">
                <h3 class="pb-15"><?= __('footer.appointment.title') ?></h3>
                <div class="flex justify-center">
                    <a href="/contact.php" class="button col-6"><?= __('footer.appointment.cta') ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="flex align-center justify-center pt-30">
        <p>
            <small>&copy; 2024 Connect & Play VOF</small>
        </p>
    </div>
</footer>
<script>
    const userId = <?= json_encode($userId) ?>;
</script>
<script src="/js/shoppingCart.js"></script>
<script src="/js/checkout.js"></script>
</body>

</html>