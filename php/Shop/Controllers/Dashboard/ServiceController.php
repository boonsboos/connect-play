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
     * Haalt alle onopgeloste en onbeantwoorde contactpogingen op
     *
     * @return Contact[]
     */
    public function getServiceInquiries(): array
    {
        return $this->contactRepository->getUnresolvedContacts();
    }

    /**
     * Markeert een contactpoging als beantwoord op basis van contactpoging ID
     *
     * @param int $ticketId de id van de contactpoging
     * @return void
     */
    public function markInquiryAsAnswered(int $ticketId): void {
        $contact = $this->updateContactStatusById($ticketId, ContactReplyStatus::Answered);
        // laat de browser een e-mail prompt openen zodat het bericht gelijk beantwoord kan worden
        header("Location: mailto:" . $contact->getEmail());
        die();
    }

    /**
     * Markeert een contactpoging als opgelost op basis van contactpoging ID
     *
     * @param int $ticketId de id van de contactpoging
     * @return void
     */
    public function markInquiryAsResolved(int $ticketId): void {
        $this->updateContactStatusById($ticketId, ContactReplyStatus::Resolved);
        // ververs de pagina, nu zal de contactpoging verdwijnen.
        $this->refresh();
    }

    /**
     * Werkt de status van een contactpoging bij op basis van contactpoging ID
     *
     * @param int $ticketId de id van de contactpoging
     * @param ContactReplyStatus $status de nieuwe status van de contactpoging
     * @return Contact de bijgewerkte contactpoging
     */
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
