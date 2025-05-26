<?php

require_once '/var/www/php/Shared/Controller.php';
require_once '/var/www/php/Shop/DataAccess/WorkshopRepository.php';
require_once '/var/www/php/Shop/Domain/Workshop.php';

class WorkshopController extends Controller
{
    private WorkshopRepository $workshopRepository;

    public function __construct() {
        $this->workshopRepository = new WorkshopRepository;
    }

    public function createWorkshop(Game $game, Workshop $workshop): void
    {
        $this->workshopRepository->createWorkshop($game, $workshop);
    }

    public function getWorkshop(int $gameId): Workshop
    {
        return $this->workshopRepository->getWorkshop($gameId);
    }

}

?>