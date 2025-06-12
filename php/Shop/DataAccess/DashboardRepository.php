<?php

class DashboardRepository
{
    private $alleButtons = [];

    public function __construct()
    {
        // de urlen in de array MOETEN nog worden aangepast!
        $this->alleButtons = [
            [
                'nameButton' => "dashboard.products.name",
                'url' =>  '/dashboard/producten.php',
                'role' => 'EMPLOYEE'
            ],
            [
                'nameButton' => 'dashboard.orders.name',
                'url' => 'volgt',
                'role' => 'EMPLOYEE',
            ],         
            [
                'nameButton' => 'dashboard.servicedesk.name',
                'url' =>  '/dashboard/service.php',
                'role' => 'EMPLOYEE'  
            ],
            [
                'nameButton' => 'dashboard.searches.name',
                'url' => '/dashboard/no_search_result.php',
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