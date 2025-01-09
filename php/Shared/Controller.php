<?php
require_once 'View.php';

abstract class Controller
{
    /**
     * @var View
     */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function updateView()
    {
        $this->view->redraw();
    }
}