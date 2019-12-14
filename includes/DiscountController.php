<?php


class DiscountController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

        public function printPageView()
    {
    	if($_SESSION['role'] !== "Naudotojas" && $_SESSION['role'] !== "Administratorius") {
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Bandymas prieiti prie puslapio be teisės", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
            $username = $this->getModel()->secureInput($_SESSION['vardas']);
            $row = $this->getModel()->getDataByString('naudotojas', 'vardas', $username);

                $controller = new model();
                $conn = $controller->returnConn();
            $result = $this->getModel()->getData("kodo_kurimas");
            $this->getView()->printDiscountForm($result);
            if(isset($_POST['codeRequestBtn']))
            {
            	$code = $this->getModel()->secureInput($_POST['kodas']);
            	$number = $this->getModel()->secureInput($_POST['kodoproc']);
            	if ($this->getModel()->newCode($code, $number))
            	{
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Senas slaptažodis sėkmingai pakeistas", $ip);
                $this->getView()->printSuccess('Nuolaida sukurta');
            	} else {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Klaida keičiant seną slaptažodį", $ip);
                $this->getView()->printDanger('Klaida');
            	}
            }
    }

        public function getTitle()
    {
        echo 'Filmų Nuolaidos - Nustatymai';
    }
}

?>