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
            // [
            //     'name' => 'dashboard.orders.name',
            //     'url' => 'volgt'
            // ],         
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
            [
                'name' => 'dashboard.datahub.name',
                'url' => '/dashboard/data-hub.php'
            ]
        ];
    }

    /**
     * Geeft de associatieve array met daarin de knoppen voor de administrator mee
     *
     * @return array[]
     */
    public function getAdminButtons(): array {
        return $this->adminButtons;
    }

    /**
     * Geeft de associatieve array met daarin de knoppen voor de gewone medewerker mee
     *
     * @return array[]
     */
    public function getEmployeeButtons(): array {
        return $this->employeeButtons;
    }
}

?>