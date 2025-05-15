<?php

// maak sessie aan om die vervolgens te vernietigen
session_start();
session_destroy();
// redirect naar login pagina
header("Location: /login.php", true, 303);
die();
