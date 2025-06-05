<?php

require_once '/var/www/php/Shop/DataAccess/WorkshopRepository.php';
require_once '/var/www/php/Shop/DataAccess/GameRepository.php';

use PHPUnit\Framework\TestCase;

class WorkshopTest extends TestCase {

    public function testCreateWorkshop() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = New GameRepository();
        $workshopRepository = New WorkshopRepository();
        
        // Act
        $gameRepository->addGame($game);
        
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120); // na het aanmaken van de Game kan de workshop pas worden gemaakt omdat deze gekoppeld is aan het ID van de Game
        $workshopRepository->createWorkshop($workshop);
        
        $getWorkshop = $workshopRepository->getWorkshop($workshop->getGameId());
        
        $workshopRepository->removeWorkshop($game->getId());
        $gameRepository->removeGame($game->getId());        
        
        // Assert
        $this->assertEquals($game->getId(), $getWorkshop->getGameId());
    }

    public function testUpdateWorkshop()
    {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = New GameRepository();
        $workshopRepository = New WorkshopRepository();
        
        // Act
        $gameRepository->addGame($game);        

        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120);
        $workshopRepository->createWorkshop($workshop);
        
        // voeg nieuwe waardes toe aan het workshop object voor het updaten
        $workshop->setMinSize(4);
        $workshop->setMaxSize(8);
        $workshop->setPrice(120);
        $workshop->setDuration(100);
       
        $workshopRepository->updateWorkshop($workshop);
        
        $getWorkshop = $workshopRepository->getWorkshop($workshop->getGameId());
        
        $workshopRepository->removeWorkshop($game->getId());
        $gameRepository->removeGame($game->getId());

        // Assert 
        // Je checkt per eigenschap of de waarde overeenkomt met de geupdate workshop.
        $this->assertEquals(4, $getWorkshop->getMinSize());
        $this->assertEquals(8, $getWorkshop->getMaxSize());
        $this->assertEquals(120, $getWorkshop->getPrice());
        $this->assertEquals(100, $getWorkshop->getDuration());
      
    }
    
    public function testRemoveWorkshop() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 3", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = New GameRepository();
        $workshopRepository = New WorkshopRepository();
        
        // Act
        $gameRepository->addGame($game);        

        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120);
        $workshopRepository->createWorkshop($workshop);
        $workshopRepository->removeWorkshop($game->getId()); // workshop wordt verwijderd en is dus niet meer beschikbaar in de database

        // na het uitvoeren van de test wordt de game en workshop verwijderd uit de database
        $gameRepository->removeGame($game->getId());
        
        // Assert
        // omdat de getWorkshop een Exception gooit, moet je deze opvangen met een try catch
        try {
            $workshopRepository->getWorkshop($game->getId());
            // lukt het ophalen van een workshop? Dan is de test gefaald
            $this->fail("Workshop is niet verwijderd.");
        } catch (Exception $e) {
            // hij moet dus een Exception krijgen om de test te laten slagen
            $this->assertEquals("Geen workshop gevonden voor deze game.", $e->getMessage()); 
        }
    }

    public function testGetWorkshopsForGame() {
        // Arange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 4", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = New GameRepository();
        $workshopRepository = New WorkshopRepository();
        
        // Act
        $gameRepository->addGame($game);

        
        // maak 2 workshops aan om ze vervolgens beide op te halen
        $workshop = new Workshop($game->getId(), 2, 4, 75.5, 120);
        $workshopRepository->createWorkshop($workshop);

        // na het uitvoeren van de test wordt de game en workshop verwijderd uit de database
        $gameRepository->removeGame($game->getId());
        $workshopRepository->removeWorkshop($game->getId());
        
        // Assert
        $this->assertCount(1, $workshopRepository->getWorkshops($game->getId()));
    }
    
}

?>