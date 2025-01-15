<?php

require_once './php/Shared/View.php';

class LoginView implements View
{

    public string $loginError;

    public function __construct()
    {
        if (isset($_GET['error'])) {
            $this->loginError = $_GET['error'];
        }
    }

    function redraw()
    {
        // TODO: Implement redraw() method.
    }
}
