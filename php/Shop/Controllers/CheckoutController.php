<?php

require_once '/var/www/php/Shared/Controller.php';

class CheckoutController extends Controller {

    public function setCurrentOrderNumber() 
    {
        if (isset($_POST["currentOrder"]) && is_numeric($_POST["currentOrder"])) {
            $_SESSION["currentOrderNumber"] = $_POST["currentOrder"];
            
            // je geeft hier een associatieve array mee, in JSON wordt het dan een object
            // is het een indext array dan blijft het een array
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Afrekenen niet mogelijk, probeer het later nog een keer!"]);
        }
    }

    // de dispatch is een recepstionist die kijkt welke action het is en voert dit uit
    public function dispatch() 
    {
        $checkoutAction = $_POST['action'] ?? null;

        switch($checkoutAction) {
            case 'setCurrentOrderNumber': 
                $this->setCurrentOrderNumber();
                break;
            default:
        }
    }

}

?>