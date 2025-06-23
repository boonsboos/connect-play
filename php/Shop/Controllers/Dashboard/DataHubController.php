<?php
require_once "/var/www/php/Shared/CSVLoader.php";
require_once "/var/www/php/Shared/Database.php";
require_once "/var/www/php/Shop/DataAccess/GameRepository.php";
class DataHubController
{
    private GameRepository $gameRepository;
    public function __construct(
        private array $data = [],
        private array $headers = [],
        private ?string $fileName = null,
        private int $max = 250,
        private ?string $error = null
    ) {
        $this->gameRepository = new GameRepository();
    }

    /**
     * Verwerk een CSV-bestand.
     *
     * @param array $file het bestand dat is geupload
     * @return bool
     * - true als het verwerken slaagt
     * - false als het verwerken fout gaat
     */
    public function processCSVFile(array $file): bool
    {
        // Laad het CSV-bestand met de CSVLoader 
        [$this->fileName, $tmpName] = CSVLoader::load($file); // destructureer de array om de originele bestandsnaam en de tijdelijke bestandsnaam te krijgen 
        $_data = CSVLoader::read($tmpName); // Lees de CSV-gegevens met de CSVLoader
        $this->headers = $_data[0] ?? []; // Neem de eerste rij als headers
        $this->data = array_slice($_data, 1); // Verwijder de eerste rij (headers) van de data

        if (empty($this->headers)) {
            $this->setError("Het CSV-bestand bevat geen geldige headers.");
            return false;
        }
        if (!$this->validateHeaders($this->headers)) {
            $this->setError("De headers komen niet overeen met de verwachte kolommen in de database.");
            return false;
        }
        if (count($this->data) > $this->max) {
            $this->setError("Het aantal rijen in het CSV-bestand is groter dan de maximale limiet van {$this->max}.");
            return false;
        }
        if (count($this->data) > count($this->gameRepository->getGames())) {
            $this->setError("Het aantal rijen in het CSV-bestand is groter dan het aantal bestaande games in de database.");
            return false;
        }
        return true;
    }

    /**
     * Retourneer de verwerkte CSV-gegevens zonder de headers.
     * De headers worden apart behandeld in de {@see getHeaders()} methode.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Retourneer de headers van de CSV-gegevens.
     *
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Retourneer de bestandsnaam van het geüploade CSV-bestand.
     *
     * @return string|null
     * - string als het bestand bestaat
     * - null als het bestand niet bestaat
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Valideert de CSV headers door ze te vergelijken
     * met de namen van de kolommen in de database
     *
     * @param array $headers de headers om te vergelijken
     * @return bool
     * - true als ze overeenkomen
     * - false als ze niet overeenkomen
     */
    private function validateHeaders(array $headers): bool
    {
        // Controleer of de headers overeenkomen met de verwachte kolommen in de database
        $expectedColumns = Database::getTableColumns("game");
        return empty(array_diff($headers, $expectedColumns));
    }

    /**
     * Retourneer de foutmelding, indien aanwezig.
     *
     * @return string|null
     * - string als er een foutmelding is
     * - null als er geen fouten zijn
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Stelt een foutmelding in
     *
     * @param string|null $error de foutmelding
     * @return void
     */
    private function setError(?string $error): void
    {
        $this->error = $error;
    }

    /**
     * Werk de database bij met de geüploade CSV-gegevens.
     * 
     * @throws Exception Als er geen CSV-bestand is geüpload of als de headers niet overeenkomen met de verwachte kolommen in de database.
     * @throws Exception Als er onbekende kolommen in de CSV zijn die niet in de database voorkomen.
     */
    public function updateDatabase(): bool
    {
        if (!$this->isUploaded()) {
            $this->setError("Er is geen CSV-bestand geüpload.");
            return false; // Als er geen bestand is geüpload, kan de database niet worden bijgewerkt
        }
        $headers = $this->getHeaders();
        $data = $this->getData();

        if (!$this->validateHeaders($headers)) {
            $this->setError("De headers komen niet overeen met de verwachte kolommen in de database.");
            return false; // Als de headers niet overeenkomen, kan de database niet worden bijgewerkt
        }

        foreach ($data as $row) {
            $assoc = array_combine($headers, $row); // Maak een associatieve array van de headers en de waarden in de rij
            if ($assoc === false) continue; // Als er een fout is bij het combineren, sla deze rij over

            $this->gameRepository->updateGameOptional($assoc);
        }
        // Reset de status na het bijwerken van de database
        $this->fileName = null;
        $this->data = [];
        $this->headers = [];
        $this->setError(null);
        return true; // Als alles goed is gegaan, retourneer true
    }

    /**
     * Controleer of er een CSV-bestand is geüpload.
     *
     * @return bool
     * - true als het bestand is geüpload
     * - false als dat niet zo is
     */
    public function isUploaded(): bool
    {
        return $this->fileName !== null && !empty($this->fileName);
    }
}
