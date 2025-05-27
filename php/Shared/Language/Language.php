<?php

/**
 * Language class voor het beheren van meertalige ondersteuning in een PHP-toepassing.
 * Deze class laadt taalbestanden op basis van de ingestelde taal en biedt een methode
 */
class Language
{
    private array $languages = ["en", "nl"];
    private array $translations = [];

    public function __construct(private string $default = 'nl')
    {
        // Start de sessie als die nog niet bestaat
        if (!isset($_SESSION)) {
            session_start();
        }

        // Stel de taal in (afhankelijk van query of sessie)
        $this->setLanguage($default);
    }
    /**
     * Stelt de huidige taal in, op basis van de URL (GET['lang']) of sessie.
     * Als geen taal is opgegeven en geen sessie bestaat, wordt de standaardtaal gebruikt.
     */
    public function setLanguage(string $default = "nl"): void
    {
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->languages)) {
            $_SESSION['language'] = $_GET['lang'];
        } elseif (!isset($_SESSION['language'])) {
            $_SESSION['language'] = $default;
        }
        // Laad de vertalingen voor de huidige taal
        $this->loadLanguageFile();
    }

    /**
     * Haalt de momenteel ingestelde taal op uit de sessie.
     */
    public function getLanguage(): string
    {
        return $_SESSION['language'] ?? $this->default; // Retourneert de taal uit de sessie of de standaardtaal
    }

    /**
     * Zoekt de vertaling op voor een gegeven sleutel.
     * Ondersteunt geneste sleutels met een puntnotatie, bijv. "title" of "nav.services". 
     */
    public function translate(string $key): string
    {
        // Controleer of het een geneste sleutel is
        if (str_contains($key, '.')) {
            $parts = explode('.', $key);    // Splitst bijvoorbeeld "nav.services" in ['nav', 'services']
            $current = $this->translations; // Start bij de volledige vertalingsarray

            if (empty($parts)) {
                return $key; // als de sleutel leeg is, geef de originele sleutel terug
            }

            // Loop door elk deel van de sleutel en navigeer door de array
            foreach ($parts as $part) {
                if (isset($current[$part])) {
                    if (!is_array($current)) return $key; // Als de huidige waarde geen array is, maar we verwachten er een, geef de sleutel terug
                    $current = $current[$part]; // Ga een niveau dieper
                } else {
                    return $key; // Sleutel niet gevonden
                }
            }

            // Uiteindelijk gevonden waarde teruggeven
            return $current;
        }
        // Geen geneste sleutel; geef directe vertaling of fallback terug
        return $this->translations[$key] ?? $key;
    }

    /**
     * Laadt het taalbestand op basis van de ingestelde taal.
     * Het bestand moet een array met vertalingen returnen.
     */
    private function loadLanguageFile(): void
    {
        $language = $this->getLanguage();
        if (!in_array($language, $this->languages)) { // Controleer of de taal geldig is
            // Als de taal niet geldig is, gebruik de standaardtaal
            $language = $this->default;
        }
        $filePath = __DIR__ . "/{$language}.php"; // Pad naar het taalbestand
        if (file_exists($filePath)) { // Controleer of het bestand bestaat
            $this->translations = require $filePath; // Laad de vertalingen uit het bestand
        } else {
            $this->translations = []; // Als het bestand niet bestaat, gebruik een lege array
        }
    }
}
