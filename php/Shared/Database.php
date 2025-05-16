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
}
