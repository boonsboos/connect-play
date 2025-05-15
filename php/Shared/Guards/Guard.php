<?php

abstract class Guard
{
    public abstract function allowed(): bool;

    public function fallbackIfNotAllowed(string $location = "/login.php"): void
    {
        if (!$this->allowed()) {
            header("Location: $location");
        }
    }
}