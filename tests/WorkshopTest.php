<?php

require_once '/var/www/php/Shop/Controllers/WorkshopController.php';
require_once '/var/www/php/Shop/Controllers/GameController.php';

use PHPUnit\Framework\TestCase;

class WorkshopTest extends TestCase {

    public function testCreateWorkshop() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameController = New GameController();
        $workshopController = New WorkshopController();
        
        // Act
        $gameController->addGame($game);
        $game = $gameController->getGameByName($game->getName());
        
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120); // na het aanmaken van de Game kan de workshop pas worden gemaakt omdat deze gekoppeld is aan het ID van de Game
        $workshopController->createWorkshop($workshop);

        // Assert 
        $getWorkshop = $workshopController->getWorkshop($workshop->getGameId());
        $this->assertEquals($game->getId(), $getWorkshop->getGameId());

        // na het uitvoeren van de test wordt de game en workshop verwijderd uit de database
        $workshopController->removeWorkshop($game->getId());
        $gameController->removeGame($game->getId());
    }

    public function testUpdateWorkshop()
    {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameController = New GameController();
        $workshopController = New WorkshopController();
        
        // Act
        $gameController->addGame($game);
        $game = $gameController->getGameByName($game->getName());
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120); // je kunt pas een workshop aanmaken zodra er een game bestaat
        $workshopController->createWorkshop($workshop);
        
        // voeg nieuwe waardes toe aan het workshop object voor het updaten
        $workshop->setMinSize(4);
        $workshop->setMaxSize(8);
        $workshop->setPrice(120);
        $workshop->setDuration(100);
       
        $workshopController->updateWorkshop($workshop);

        // Assert 
        // Je checkt per eigenschap of de waarde overeenkomt met de geupdate workshop.
        $getWorkshop = $workshopController->getWorkshop($workshop->getGameId());
        $this->assertEquals(4, $getWorkshop->getMinSize());
        $this->assertEquals(8, $getWorkshop->getMaxSize());
        $this->assertEquals(120, $getWorkshop->getPrice());
        $this->assertEquals(100, $getWorkshop->getDuration());

        // na het uitvoeren van de test wordt de game en workshop verwijderd uit de database
        $workshopController->removeWorkshop($game->getId());
        $gameController->removeGame($game->getId());
    }
    
    public function testRemoveWorkshop() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameController = New GameController();
        $workshopController = New WorkshopController();
        
        // Act
        $gameController->addGame($game);
        $game = $gameController->getGameByName($game->getName());
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120); // je kunt pas een workshop aanmaken zodra er een game bestaat
        
        $workshopController->createWorkshop($workshop);
        $workshopController->removeWorkshop($game->getId());

        // Assert
        $this->assertNull($workshopController->getWorkshop($game->getId()));
        
        // na het uitvoeren van de test wordt de game en workshop verwijderd uit de database
        $gameController->removeGame($game->getId());
    }

    public function testGetWorkshopsForGame() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameController = New GameController();
        $workshopController = New WorkshopController();
        
        // Act
        $gameController->addGame($game);
        $game = $gameController->getGameByName($game->getName());
        
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120);
        $workshopController->createWorkshop($workshop);
        $workshop = new Workshop($game->getId(), 4, 8, 100, 220);
        $workshopController->createWorkshop($workshop);

        // Assert
        $this->assertCount(2, $workshopController->getWorkshops($game->getId()));
    }
    
}

?>