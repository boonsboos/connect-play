<?php
require_once '/var/www/php/Profile/Domain/Contact.php';
require_once '/var/www/php/Shared/Database.php';

require_once "/var/www/php/Profile/Domain/ContactReplyStatus.php";

class ContactRepository
{
    private PDO $db;
    public function __construct()
    {
        try {
            $this->db = Database::connect();
        } catch (PDOException $e) {
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

        $stmt->closeCursor();

        return null;
    }

    /**
     * @return array|null
     */
    public function getUnresolvedContacts(): ?array
    {
        $stmt = $this->db->prepare("CALL get_unresolved_contacts()");

        $stmt->execute();

        $contacts = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $contacts[] = new Contact(
                $row['id'],
                $row['first_name'],
                $row['last_name'],
                $row['email'],
                $row['message'],
                ContactReplyStatus::from($row['status']),
                $row['created_at']
            );
        }

        $stmt->closeCursor();

        return $contacts;
    }

    /**
     * Updatet de status van een contactpoging naar of beantwoord of opgelost
     * @param Contact $contact de contactpoging
     * @return bool als de query slaagt
     */
    public function updateContactStatus(Contact $contact): bool
    {
        // kan niet updaten naar ongelezen
        if ($contact->getStatus() == ContactReplyStatus::Unread) {
            return false;
        }

        $stmt = $this->db->prepare("CALL update_contact_status(:id, :status)");
        $success = $stmt->execute([
            ':id' => $contact->getId(),
            ':status' => $contact->getStatus()
        ]);

        $stmt->closeCursor();
        return $success;
    }
}
