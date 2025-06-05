<?php

require_once '/var/www/php/Shop/DataAccess/GameRepository.php';

use PHPUnit\Framework\TestCase;

class GameTest extends TestCase {

    public function testCreateGame()
    {
        // Arrange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 1", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = New GameRepository();
        
        // Act
        $gameRepository->addGame($game);
        $getGame = $gameRepository->getGame($game->getId());
        
        // na het uitvoeren van de test wordt de game verwijderd uit de database
        $gameRepository->removeGame($game->getId());

        // Assert 
        $this->assertEquals($game->getId(), $getGame->getId());
    }

    public function testUpdateGame()
    {
        // Arrange
        $game = New Game(8, 25.5, 120, "Dark Masters of Dark 2", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = new GameRepository();

        // Act
        $gameRepository->addGame($game);

        $game->setName("Masters of Dark");
        $game->setPrice(35.5);
        $game->setDuration(120);
        $game->setLeftInStock(5);

        $gameRepository->updateGame($game); // voer de update uit
        $updatedGame = $gameRepository->getGame($game->getId()); // haal de game met nieuwe gegevens weer op
      
        // na het uitvoeren van de test wordt het spel verwijderd uit de database
        $gameRepository->removeGame($game->getId());


        // Assert
        $this->assertEquals("Masters of Dark", $updatedGame->getName());
        $this->assertEquals(35.5, $updatedGame->getPrice());
        $this->assertEquals(120, $updatedGame->getDuration());
        $this->assertEquals(5, $updatedGame->getLeftInStock());
    }

    public function testRemoveGame()
    {
        // Arrange
        $game = new Game(8, 25.5, 120, "Dark Masters of Dark 3", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = new GameRepository();

        // Act
        $gameRepository->addGame($game);
        $gameRepository->removeGame($game->getId());

        // Assert
        // omdat de getGame een Exception gooit, moet je deze opvangen met een try catch
        try {
            $gameRepository->getGame($game->getId());
            $this->fail("Game is niet verwijderd.");
        } catch (Exception $e) {
            $this->assertEquals("Game niet gevonden.", $e->getMessage());
        }
    }

    public function testGetGames() {
        // Arrange
        $game1 = new Game(8, 25.5, 120, "Dark Masters of Dark 4", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $game2 = new Game(6, 55, 100, "Dark Masters of Dark 5", "En zoek spel naar de verborgen schatten", "Makkelijk", 12);
        $gameRepository = new GameRepository();

        // Act
        $gameRepository->addGame($game1);
        $gameRepository->addGame($game2);
        $allGames = $gameRepository->getGames(); // haal alle games op en sla dit op in array
      
        // na het uitvoeren van de test worden de spellen verwijderd uit de database
        $gameRepository->removeGame($game1->getId());
        $gameRepository->removeGame($game2->getId());
        
        // EXTRA COMMIT AAN TOEVOEGEN
        $getGamesIsTrue = array_filter($allGames, function($game) use ($game1, $game2) {
            return $game1->getId() == $game->getId() || $game2->getId() == $game->getId();
        });

        // Assert
        $this->assertCount(2, $getGamesIsTrue);
    }

}

?>