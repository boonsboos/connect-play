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

    public function markInquiryAsAnswered(int $ticketId) {
        $contact = $this->updateContactStatusById($ticketId, ContactReplyStatus::Answered);
        header("Location: mailto:" . $contact->getEmail());
        die();
    }

    public function markInquiryAsResolved(int $ticketId) {
        $this->updateContactStatusById($ticketId, ContactReplyStatus::Resolved);
    }

    private function updateContactStatusById(int $ticketId, ContactReplyStatus $status): Contact {
        $contact = $this->contactRepository->getContactById($ticketId);
        $contact->setStatus($status);
        $this->contactRepository->updateContactStatus($contact);
        return $contact;
    }
}
