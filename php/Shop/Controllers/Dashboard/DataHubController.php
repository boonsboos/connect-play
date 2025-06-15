<?php
require_once "/var/www/php/Shared/CSVLoader.php";
require_once "/var/www/php/Shared/Database.php";
require_once "/var/www/php/Shop/DataAccess/GameRepository.php";
class DataHubController
{
    private GameRepository $gameRepository;
    public function __construct(private array $data = [], private array $headers = [], private ?string $uploaded = null, private int $max = 250, private ?string $error = null)
    {
        $this->gameRepository = new GameRepository();
    }

    /**
     * Upload en verwerk een CSV-bestand.
     */
    public function uploadCSVFile($file): bool
    {
        $this->uploaded = CSVLoader::load($file); // Laad het CSV-bestand via de CSVLoader
        $_data = CSVLoader::read($this->uploaded); // Lees de CSV-gegevens met de CSVLoader
        $this->headers = $_data[0]; // Verwijder de eerste rij (headers) van de data
        $this->data = array_slice($_data, 1); // Verwijder de eerste rij (headers) van de data

        if (count($this->headers) === 0) {
            $this->setError("Het CSV-bestand bevat geen geldige headers.");
            return false;
        }
        if(!$this->validateHeaders($this->headers)) {
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
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Retourneer de headers van de CSV-gegevens.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    private function validateHeaders(array $headers): bool
    {
        // Controleer of de headers overeenkomen met de verwachte kolommen in de database
        $expectedColumns = Database::getTableColumns("game");
        return empty(array_diff($headers, $expectedColumns));
    }

    /**
     * Retourneer de foutmelding, indien aanwezig.
     */
    public function getError(): ?string
    {
        return $this->error;
    }

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
        $this->uploaded = null; 
        $this->data = []; 
        $this->headers = [];
        $this->setError(null);
        return true; // Als alles goed is gegaan, retourneer true
    }

    /**
     * Controleer of er een CSV-bestand is geüpload.
     */
    public function isUploaded(): bool
    {
        return $this->uploaded !== null && !empty($this->uploaded);
    }
}
