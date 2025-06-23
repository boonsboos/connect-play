<?php
require_once "/var/www/php/Profile/DataAccess/ContactRepository.php";

class ServiceController
{
    private readonly ContactRepository $contactRepository;

    public function __construct()
    {
        $this->contactRepository = new ContactRepository();
    }

    /**
     * @return Contact[]
     */
    public function getServiceInquiries(): array
    {
        return $this->contactRepository->getUnresolvedContacts();
    }

    public function markInquiryAsAnswered(int $ticketId): void {
        $contact = $this->updateContactStatusById($ticketId, ContactReplyStatus::Answered);
        // laat de browser een e-mail prompt openen zodat het bericht gelijk beantwoord kan worden
        header("Location: mailto:" . $contact->getEmail());
        die();
    }

    public function markInquiryAsResolved(int $ticketId): void {
        $this->updateContactStatusById($ticketId, ContactReplyStatus::Resolved);
        // ververs de pagina, nu zal de contactpoging verdwijnen.
        $this->refresh();
    }

    private function updateContactStatusById(int $ticketId, ContactReplyStatus $status): Contact {
        $contact = $this->contactRepository->getContactById($ticketId);
        $contact->setStatus($status);

        try {
            $this->contactRepository->updateContactStatus($contact);
        } catch (PDOException) {
            $this->refresh();
        }

        return $contact;
    }

    /**
     * @return never omdat deze functie nooit retourneert
     */
    private function refresh(): never {
        header("Location: service.php");
        die();
    }
}
