<?php


class MainPageController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Connection()
    {
        $controller = new model();
        $conn = $controller->returnConn();
        return $conn;
    }

    public function getTitle()
    {
        echo 'Filmų - pagrindinis puslapis!';
    }

    public function printPageView()
    {
        $this->getView()->printIndexPage();
    }

    
}