<?php


class AdminController extends  MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        if($_SESSION['role'] !== 'Administratorius' || $_SESSION['busena'] === 'Užblokuotas') {
            $this->redirect_to_another_page('index.php', 0);
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Bandyta neleistinai jugntis prie puslapio", $ip);
        }
        else {

            if (isset($_GET['id']) && !empty($_GET['id']) && $_GET['id'] != '' && isset($_GET['role']) && !empty($_GET['role']) && $_GET['role'] != '') {
                $id = $this->getModel()->secureInput($_GET['id']);
                $role = $this->getModel()->secureInput($_GET['role']);
                $data = $this->getModel()->getDataByColumnFirst("naudotojas", "id", $id);
                if ($data['fk_role'] !== $_GET['role'] && $data['id'] == $_GET['id']) {
                    $this->getModel()->updateDataOneColumn("naudotojas", $_GET['id'], "fk_role", $role);
                    $roleName = $role;
                    //$ip = $this->getModel()->getIP();
                    //$this->getModel()->updateLog("Pekeista privilegija: ".$data['slapyvardis'].": ".$roleName." ", $ip);
                    $this->printSuccess("sėkmingai pakeista privilegija");
                }
            } else if (isset($_GET['busenaid']) && !empty($_GET['busenaid']) && $_GET['busenaid'] != '' && isset($_GET['busena']) && !empty($_GET['busena']) && $_GET['busena'] != '') {
                $id = $this->getModel()->secureInput($_GET['busenaid']);
                $busena = $this->getModel()->secureInput($_GET['busena']);
                $data = $this->getModel()->getDataByColumnFirst("naudotojas", "id", $id);
                if ($data['fk_naudotojo_busena'] !== $_GET['busena'] && $data['id'] == $_GET['busenaid']) {
                    $this->getModel()->updateDataOneColumn("naudotojas", $_GET['busenaid'], "fk_naudotojo_busena", $busena);
                    $busenaName = $busena;
                    //$ip = $this->getModel()->getIP();
                    //$this->getModel()->updateLog("Pekeista privilegija: ".$data['slapyvardis'].": ".$roleName." ", $ip);
                    $this->printSuccess($busena);
                    $this->printSuccess("sėkmingai pakeista busena");
                }

            } else if (isset($_GET['deleteid']) && !empty($_GET['deleteid']) && $_GET['deleteid'] != '')
            {
                $id = $this->getModel()->secureInput($_GET['deleteid']);
                $data = $this->getModel()->getDataByColumnFirst("naudotojas", "id", $id);
                if ($this->getModel()->deleteData('naudotojas', $data['id']))
                {
                    $this->getView()->printSuccess('Trinama');
                    $this->redirect_to_another_page('admin.php', 1);
                } else {
                    $this->getView()->printDanger('Klaida');
                }
            }
        }


        $results = $this->getModel()->getData("naudotojas");
        $this->getView()->printAdminPanel($results);
        // TODO: Implement printPageView() method.
    }

    public function getTitle()
    {
        echo 'Filmų - Valdiklis';
        // TODO: Implement getTitle() method.
    }

    public function printEditUserView()
    {
        if($_SESSION['role'] !== 'Administratorius' || $_SESSION['busena'] === 'Užblokuotas')
        {
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Naudotojas neleistinai bandė panaudoti puslapįu", $ip);
            //$this->redirect_to_another_page('index.php', 0);
        }
        if(!isset($_GET['id']))
        {
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog("Naudotojas neleistinai bandė panaudoti puslapįu", $ip);
            $this->printDanger('Ivyko klaida!');
            $this->redirect_to_another_page('admin.php', 0);
            return;
        }

        $check = true;
        $sum = 0;
        $id = $this->getModel()->secureInput($_GET['id']);
        foreach ($_POST as $param_name => $param_val) {
            $value = $this->getModel()->secureInput($param_val);
            if(isset($_POST['request']) && $_POST['request'] == "visiDuomenys") {
                if ($param_name !== 'id' && $param_name != "vardas" && $param_name != "request" &&  isset($value) && !empty($value) && $value != '') {

                    $this->getModel()->updateDataOneColumn("naudotojas", $id, $param_name, $value);
                } else if (($param_name == 'el_pastas') && ( $param_name != "request" && !isset($value) || empty($value) || $value == '')) {
                    //$ip = $this->getModel()->getIP();
                    //$this->getModel()->updateLog("Vartotojo tvarkymo Laukas: email yra tuščias", $ip);
                    $this->printDanger('Laukai yra tušti');
                    $check = false;
                } else if ($param_name === 'vardas') {
                    //$ip = $this->getModel()->getIP();
                    //$this->getModel()->updateLog("Vartotojo tvarkymo Laukas: slapyvardis yra tuščias", $ip);
                    $this->printDanger('Laukai yra tušti');
                    $check = false;
                }
                $sum++;
            }
        }


        if(isset($_POST['request']) && $_POST['request'] == "slaptazodisSubmit" ) {

            $password = $this->getModel()->secureInput($_POST['slaptazodis']) ;
            $passwordCheck = $this->getModel()->secureInput($_POST['slaptazodisPakartoti']);
            $id = $this->getModel()->secureInput($_GET['id']);

            if ($this->getModel()->changePasswdAdmin($id, $password, $passwordCheck))
            {

                $username = $this->getModel()->getDataByColumnFirst('naudotojas', 'id', $id);
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog(" Pakeitė sėkmingai ".$username['vardas']." slaptažodį", $ip);
                $this->getView()->printSuccess('Slaptažodis sėkmingai pakeistas');
            } else {
                //$ip = $this->getModel()->getIP();
                //$this->getModel()->updateLog("Slaptažodžio keitimo klaida", $ip);
                $this->getView()->printDanger('Klaida');
            }

        }

        if($check === true && $sum > 0)
        {


            $username = $this->getModel()->getDataByColumnFirst('naudotojas', 'id', $id);
            //$ip = $this->getModel()->getIP();
            //$this->getModel()->updateLog(" Pakeitė sėkmingai ".$username['vardas']." duomenis", $ip);
            $this->printSuccess("Sėkmingai pakeisti duomenys");
        }

        $content = $this->getModel()->getDataByColumnFirst("naudotojas", 'id', $id);
        $this->getView()->printEditUserAsAdmin($content);


    }
}