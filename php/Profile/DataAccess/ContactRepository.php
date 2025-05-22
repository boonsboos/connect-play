<?php
require_once '/var/www/php/Profile/Domain/Contact.php';
require_once '/var/www/php/Shared/Database.php';

class ContactRepository
{
    private PDO $db;
    public function __construct()
    {
        try {
            // Maak een nieuwe databaseverbinding
            $this->db = Database::connect();
        } catch (PDOException $e) {
            // Log de foutmelding of geef een foutmelding weer
            echo "Fout bij het verbinden met de database: " . $e->getMessage();
            exit;
        }
    }

    public function addContact(Contact $contact): void
    {
        $stmt = $this->db->prepare("CALL add_contact(:first_name, :last_name, :email, :message)");
        $stmt->execute([
            ':first_name' => $contact->getFirstName(),
            ':last_name' => $contact->getLastName(),
            ':email' => $contact->getEmail(),
            ':message' => $contact->getMessage()
        ]);
    }

    public function getContactById(int $id): ?Contact
    {
        $stmt = $this->db->prepare("CALL get_contact(:id, NULL)");
        $stmt->execute([':id' => $id]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contact) {
            return new Contact(
                $contact['id'],
                $contact['first_name'],
                $contact['last_name'],
                $contact['email'],
                $contact['message'],
                $contact['created_at']
            );
        }

        return null;
    }
}
