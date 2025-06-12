<?php

class DashboardController
{
    private array $employeeButtons;
    private array $adminButtons;

    public function __construct()
    {
        $this->employeeButtons = [
            [
                'name' => "dashboard.products.name",
                'url' =>  '/dashboard/producten.php'
            ],
            [
                'name' => 'dashboard.orders.name',
                'url' => 'volgt'
            ],         
            [
                'name' => 'dashboard.servicedesk.name',
                'url' =>  '/dashboard/service.php'
            ]
        ];
        $this->adminButtons = [
            [
                'name' => 'dashboard.searches.name',
                'url' => '/dashboard/no_search_result.php'
            ],
        ];
    }

    // je maakt altijd nog een functie voor het ophalen van je gegevens
    public function getAdminButtons(): array {
        return $this->adminButtons;
    }

    public function getEmployeeButtons(): array {
        return $this->employeeButtons;
    }
}

?>