<?php
class LoginController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        if($_SESSION['busena'] === 'Užblokuotas' || $_SESSION['role'] >= 1)
        {
            //$this->getModel()->updateLog("Neleistinas prisijungimas prie puslapio be teisių", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }

        $this->getView()->printLoginPage();
        if(isset($_POST['loginButton']))
        {
            if($this->getModel()->loginMe($_POST['username'], $_POST['password']))
            {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Vartotjas prijungtas prie sistemos!", $ip);
                $this->getView()->printSuccess('Jūs prijungtas prie sistemos!');
                $this->redirect_to_another_page('index.php', 0);
            }
            else
            {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Vartotjas neprisijungė prie sistemos! ".$_POST['username']." ", $ip);
                $this->getView()->printDanger('Jūs neprijungtas prie sistemos!');
            }
        }
    }

    public function getTitle()
    {
        echo 'Filmų - prisijungimas';
    }
}