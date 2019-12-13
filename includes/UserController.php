<?php


class UserController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    // register.php
    public function printPageView()
    {
        if($_SESSION['busena'] === '1' || $_SESSION['role'] > 0)
        {
            $this->redirect_to_another_page('index.php', 0);
        }

        $this->getView()->printRegisterForm();

        if (isset($_POST['registerButton']))
        {
            $username = $_POST['username'];
            $surname = $_POST['surname'];
            $password = $_POST['password'];
            $el_pastas = $_POST['el_pastas'];
            $passwordRepeat = $_POST['passwordRepeat'];
            $date = date('Y-m-d H:i:s');


            if($this->getModel()->registerUser($username, $surname, $el_pastas, $password, $passwordRepeat, $date)) {
                //$this->getModel()->updateLog("Registracija sėkminga!", $username);
                $this->getView()->printSuccess('Registracija sėkminga!');
            } else {
                //$this->getModel()->updateLog("Registracijos klaida", $username);
                $this->getView()->printDanger('Klaida');
            }
        }
    }

    public function getTitle()
    {
        echo 'Filmų - Registracija';
    }
}