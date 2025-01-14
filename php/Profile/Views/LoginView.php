<?php

require_once './php/Shared/View.php';

class Loginview implements View {

    public bool $loginResult;

    public function __construct()
    {
        if (!isset($_GET['result'])) {
            $this->__construct_default();
            return;
        }

        if ($_GET['result'] != "success") {
            $this->loginResult = false;
        } else {
            $this->loginResult = true;
        }
    }

    private function __construct_default(): void
    {
        $this->loginResult = true;
    }

    function redraw()
    {
        // TODO: Implement redraw() method.
    }
}