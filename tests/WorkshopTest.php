<?php

require_once '/var/www/php/Shop/Controller/WorkshopController.php';
require_once '/var/www/php/Shop/Controller/GameController.php';

class WorkshopTest extends TestCase {

    public function testCreateWorkshop() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameController = New GameController();
        $workshop = new Workshop(1, 2, 4, 75.5, 120);
        $workshopController = New WorkshopController();
        
        // Act
        $gameController->addGame($game);
        $game = $gameController->getGameByName($game->getName());

        $workshopController->createWorkshop($game, $workshop);


        // Assert 
        $getWorkshop = $workshopController->getWorkshop($workshop->getGameId());
        $this->assertEquals($game->getId(), $getWorkshop->getGameId());
        
    }
}

?>