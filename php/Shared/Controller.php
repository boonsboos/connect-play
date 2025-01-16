<?php
require_once 'View.php';

abstract class Controller
{
    /**
     * @var View
     */
    protected $view;

    public function __construct()
    {
        $this->view = null;
    }

    public function updateView()
    {
        $this->view->redraw();
    }
}