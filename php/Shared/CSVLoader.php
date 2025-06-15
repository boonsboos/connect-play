<?php

class CSVLoader
{
    private function __construct() {} // Voorkom dat er een instantie van deze klasse wordt gemaakt

    /**
     * Laad een CSV-bestand vanuit een geüpload bestand
     * @param array $file Een array met 'tmp_name' en 'name' sleutels van het geüploade bestand
     */
    public static function load($file): ?string
    {
        $tmpName = $file['tmp_name'] ?? null;
        $originalName = $file['name'] ?? '';

        // Controleer of het bestand bestaat, leesbaar is en een CSV-bestand is.
        // "tmp_name" is de tijdelijke locatie van het geüploade bestand, en "name" is de originele bestandsnaam.
        if (!$tmpName || !file_exists($tmpName) || !is_readable($tmpName) || strtolower(pathinfo($originalName, PATHINFO_EXTENSION)) !== 'csv') {
            return null; // Retourneer null als het bestand niet geldig is
        }

        return $tmpName; // Retourneer de tijdelijke bestandsnaam voor verdere verwerking
    }

    /**
     * Lees het eerder geladen CSV-bestand
     * @param string $file Het geüploade bestand, verwacht een array met 'tmp_name' en 'name' sleutels
     * @return ?array Een array met de ingelezen CSV-gegevens, of een null als het bestand ongeldig is
     */
    public static function read(string $file): array
    {
        $data = [];
        try {
            if (($handle = fopen($file, 'r')) !== false) { // Open het bestand in leesmodus
                // Lees de CSV-gegevens regel voor regel
                while (($row = fgetcsv($handle, 0, ',', '"', '"')) !== false) {
                    // Voeg de regel toe aan de data-array
                    $data[] = $row;
                }
                fclose($handle); // Sluit het bestand na het lezen. Als je dit niet doet, kan het bestand vergrendeld blijven.
            }
        } catch (Exception $e) {
            echo "Fout bij het lezen van het CSV-bestand: " . $e->getMessage();
            return [];
        }
        return $data;
    }
}
