<?php
class Database
{
    // Deze variabele houdt de databaseverbinding bij.
    // Het is 'static' zodat het gedeeld wordt over de hele applicatie
    // en '?PDO' betekent dat het ofwel een PDO-object is of null.
    private static ?PDO $connection = null;

    // De constructor is leeg omdat deze klasse alleen statische methodes gebruikt.
    public function __construct() {}

    /**
     * Verbindt met de database en geeft het PDO-object terug.
     * Als er al een verbinding is, wordt die hergebruikt.
     *
     * @throws PDOException bij verbindingsfouten
     */
    public static function connect(
        string $servername = "mariadb", // De servernaam of IP-adres van de database
        string $username = "root",      // De gebruikersnaam voor de database
        string $password = "",          // Het wachtwoord voor de database
        string $name = "webshop"        // De naam van de database
    ): PDO {
        // Alleen verbinden als er nog geen bestaande verbinding is
        if (self::$connection === null) {
            // Maak een nieuwe PDO-verbinding met de opgegeven gegevens
            self::$connection = new PDO("mysql:host={$servername};dbname={$name};charset=utf8mb4", $username, $password);

            // Zorg dat fouten als exceptions worden gegooid (makkelijker om op te vangen)
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Zorg dat alle opgehaalde gegevens als associatieve arrays worden teruggegeven
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        // Geef de bestaande of nieuwe verbinding terug
        return self::$connection;
    }

    /**
     * Haalt de kolomnamen van een opgegeven tabel op.
     * Handig voor als je wilt checken 
     * 
     * @param string $tableName De naam van de tabel waarvan de kolommen moeten worden opgehaald.
     * @return array Een array met de kolomnamen van de opgegeven tabel.
     */
    public static function getTableColumns(string $tableName): array
    {
        if (empty($tableName)) {
            throw new InvalidArgumentException("Tabelnaam mag niet leeg zijn.");
        }
        // Zorg dat we verbonden zijn met de database
        self::connect();

        // Bereid een SQL-query voor om de kolomnamen van de opgegeven tabel op te halen
        $stmt = self::$connection->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'webshop' AND TABLE_NAME = :tableName;");
        $stmt->execute([
            ':tableName' => $tableName
        ]);
        $columns = array_column($stmt->fetchAll(), 'COLUMN_NAME'); // Haal de kolomnamen op uit het resultaat
        $stmt->closeCursor();

        return $columns;
    }
}
