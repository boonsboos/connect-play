<?php
require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Profile/DataAccess/ContactRepository.php';

class ContactController extends Controller
{
    private ContactRepository $contactRepository;

    public function __construct()
    {
        $this->contactRepository = new ContactRepository();
    }

    public function sendContactForm(array $data): void
    {
        $firstname = htmlspecialchars($data['first-name']);
        $lastname = htmlspecialchars($data['last-name']);
        $email = htmlspecialchars($data['email']);
        $message = htmlspecialchars($data['message']);

        $contact = new Contact(0, $firstname, $lastname, $email, $message);

        $this->contactRepository->addContact($contact);

        header("Location: /contact.php?success=" . urlencode('Bedankt voor uw bericht, ' . $firstname . ' ' . $lastname . '. We nemen zo snel mogelijk contact met u op.'));
        die;
    }
}
