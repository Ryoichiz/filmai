<?php

class DiscountController extends  MainController implements iController
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
                $this->redirect_to_another_page('discount.php', 1);
            	} else {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Klaida keičiant seną slaptažodį", $ip);
                $this->getView()->printDanger('Klaida');
            	}
            }
    }

        public function printDiscountView($id)
        {
            if($_SESSION['role'] !== "Administratorius") {

            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Bandymas prieiti prie puslapio be teisės", $ip);
            $this->redirect_to_another_page('index.php', 0);
            }

                $this->getView()->printDiscount();
                if (isset($_POST['codePutBtn']))
                {
                    $id = $this->getModel()->secureInput($id);
                    $cost = $_POST['kodas'];
                    $row = $this->getModel()->getDataByString('kodo_kurimas', 'kodas', $cost);
                    if (!empty($cost) && !empty($id))
                    {
                        if($this->getModel()->updateDataOneColumn("filmas",$id,"fk_nuolaida",$row['id']))
                        {
                            $this->getView()->printSuccess('Pridėta nuolaida');
                            $this->redirect_to_another_page('movies.php', 1);
                        }else
                        {
                            $this->getView()->printDanger('Klaida');
                        }
                    }
                    else
                    {
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