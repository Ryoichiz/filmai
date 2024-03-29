<?php


class ProfileController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    // settings.php

    public function printPageView()
    {
        if($_SESSION['role'] !== "Naudotojas" && $_SESSION['role'] !== "Administratorius") {
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Bandymas prieiti prie puslapio be teisės", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
        else if (isset($_SESSION['vardas']))
        {
            $username = $this->getModel()->secureInput($_SESSION['vardas']);
            $row = $this->getModel()->getDataByString('naudotojas', 'vardas', $username);

                $controller = new model();
                $conn = $controller->returnConn();

            $this->getView()->printSettingsForm($row['vardas'], $row['pavarde'],
                $row['el_pastas'], $row['id']);
            $this->getView()->printChangePasswordForm();
            $this->getView()->printDeleteButton();
        }
        if (isset($_POST['saveSettingsBtn']))
        {
            $email = $this->getModel()->secureInput($_POST['email']);
            $surname = $this->getModel()->secureInput($_POST['surname']);
            if ($this->getModel()->updateUser($username, $email, $surname))
            {
               // $ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Vartotojo pakeitimai iššaugoti", $ip);
                $this->getView()->printSuccess('Pakeitimai išsaugoti');
                $this->redirect_to_another_page('profile.php', 1);
            } else {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Vartotojo pakeitimai neišsaugoti", $ip);
                $this->getView()->printDanger('Klaida');
            }
        }

        if (isset($_POST['changePasswdBtn']))
        {
            $username = $this->getModel()->secureInput($_SESSION['vardas']);
            $oldPasswd = $this->getModel()->secureInput($_POST['oldPasswd']);
            $newPasswd = $this->getModel()->secureInput($_POST['newPasswd']);
            $repeatNewPasswd = $this->getModel()->secureInput($_POST['repeatNewPasswd']);
            if ($this->getModel()->changePasswd($username, $oldPasswd, $newPasswd, $repeatNewPasswd))
            {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Senas slaptažodis sėkmingai pakeistas", $ip);
                $this->getView()->printSuccess('Slaptažodis sėkmingai pakeistas');
            } else {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Klaida keičiant seną slaptažodį", $ip);
                $this->getView()->printDanger('Klaida');
            }
        }

        if (isset($_POST['deleteUserBtn']))
        {
            $vardas = $_SESSION['vardas'];
            $data = $this->getModel()->getDataByString("naudotojas","vardas",$vardas);
            echo $data['id'];
            if($this->getModel()->deleteData('naudotojas', $data['id']))
            {
                $this->getView()->printSuccess('Trinama');
                $this->redirect_to_another_page('index.php', 1);
            } else {
                $this->getView()->printDanger('Klaida');
            }
        }
    }

    public function printPageSells()
    {
        if($_SESSION['role'] !== "Naudotojas" && $_SESSION['role'] !== "Administratorius") {
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Bandymas prieiti prie puslapio be teisės", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
        $list = $this->getModel()->getSells();
        $this->getView()->printSellForm($list);
    }

    public function getTitle()
    {
        echo 'Filmų profilio - Nustatymai';
    }
}