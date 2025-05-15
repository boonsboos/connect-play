<?php

abstract class Guard
{
    /**
     * Subklasses moeten deze check zelf implementeren.
     *
     * @return bool true als de gebruiker is toegestaan, false als dat niet zo is.
     */
    public abstract function allowed(): bool;

    /**
     * Redirect een gebruiker naar een pagina als ze niet toegestaan zijn.
     * @param string $location de pagina waar naar geredirect wordt.
     * @return void
     */
    public function redirectIfNotAllowed(string $location = "/login.php"): void
    {
        if (!$this->allowed()) {
            header("Location: $location");
        }
    }
}