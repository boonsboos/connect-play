<?php

class DashboardRepository
{
    private $alleButtons = [];

    public function __construct()
    {
        // de urlen in de array MOETEN nog worden aangepast!
        $this->alleButtons = [
            [
                'nameButton' => 'Producten',
                'url' =>  'volgt',
                'role' => 'EMPLOYEE'  
            ],
            [
                'nameButton' => 'Bestellingen',
                'url' => 'volgt',
                'role' => 'EMPLOYEE',
            ],         
            [
                'nameButton' => 'ServiceDesk',
                'url' =>  'volgt',
                'role' => 'EMPLOYEE'  
            ],
            [
                'nameButton' => 'Niet Gevonden Artikelen',
                'url' => 'volgt',
                'role' => 'ADMINISTRATOR',
            ],
        ];
    }

    // je maakt altijd nog een functie voor het ophalen van je gegevens
    public function getButtons(){
        return $this->alleButtons;
    }
}

?>