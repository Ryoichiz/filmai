<?php

class MainController {

    private $model;
    private $view;

    function __construct()
    {
        $this->model = new Model();
        $this->view = new View();
    }

    // static method to print navigation bar.
    // $location means which element should be highlighted (the one you are in)
    public static function printNavigationBar($location)
    {
        View::printNavbar($location);
    }

    // gets View (front-end) entity.
    protected function getView()
    {
        return $this->view;
    }

    // Gets Model entity which communicates with DB.
    protected function getModel()
    {
        return $this->model;
    }

    // This function log outs user.
    protected function logout()
    {
        $this->model->logoutMe();
    }

    // This function prints the navigation bar.
    protected function printNavBar($location)
    {
        $this->view->printNavbar($location);
    }

    // You can use this function to redirect user to another page.
    protected function redirect_to_another_page($urlDestination, $delay)
    {
        echo '<meta http-equiv="refresh" content="'.$delay.'; url='.$urlDestination.'" />';
    }

    // This function check if you are logged in.
    protected function amILoggedIn()
    {
        if($_SESSION['id'] == "0")
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // Bootstrap success (green) message.
    protected function printSuccess($text)
    {
        $this->view->printSuccess($text);
    }

    // Bootstrap danger (error) message.
    protected function printDanger($text)
    {
        $this->view->printDanger($text);
    }

    protected function getDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    protected function getDate()
    {
        return date('Y-m-d');
    }
}