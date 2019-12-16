<?php
    session_start();
    include('includes/loadControl.php');
    $controller = new MainPageController();

    $pavadinimas = $_POST['pavadinimas'];
    $metai = $_POST['metai'];
    $trukme = $_POST['trukme'];
    $aprasymas = $_POST['aprasymas'];
    $kaina = $_POST['kaina'];
    $anonsas = $_POST['anonsas'];
    $paveiksliukas = $_FILES['paveiksliukas'];

    $pav_vardas = $_FILES['paveiksliukas']['name'];
    $pav_tmp_place = $_FILES['paveiksliukas']['tmp_name'];
    $pav_size = $_FILES['paveiksliukas']['size'];
    $pav_err = $_FILES['paveiksliukas']['error'];
    $pav_type = $_FILES['paveiksliukas']['type'];

    $fileExt = explode('.', $pav_vardas);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){
        if($pav_err === 0){
            if($pav_size < 1000000){
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = 'uploads/'.$fileNameNew;
                move_uploaded_file($pav_tmp_place, $fileDestination);
                //header("Location: movies.php");
            }else{
                echo "Per didelis failas!";
            }
        }else{
            echo "Keliant įvyko klaida";
        }
    }else {
        echo "Negalima kelti šio tipo failus";
    }

    date_default_timezone_set("Europe/Vilnius");
        $dbConfigFile = fopen("./includes/database.config", "r") or die("Unable to open file!");
        $dbConfigFileString =  fgets($dbConfigFile);
        $dbConfigLines = explode(":", $dbConfigFileString);
        fclose($dbConfigFile);
        $server = $dbConfigLines[0];
        $dbUser = $dbConfigLines[1];
        $dbPassword = $dbConfigLines[2];
        $dbName = $dbConfigLines[3];
        $conn = new mysqli($server,$dbUser, $dbPassword, $dbName);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            $sql = "INSERT INTO filmas (pavadinimas,isleidimo_metai,trukme,aprasymas,ivertinimas,kaina,paveiksliukas, anonsas)
            VALUES ('$pavadinimas','$metai','$trukme','$aprasymas','0', '$kaina','$fileNameNew','$anonsas')";
            //$result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");
            $query_run1 =mysqli_query($conn,$sql);
            if($query_run1){
                echo '<script type="text/javascript"> alert("Filmas įrašytas") </script>';
                header("Location: movies.php");
             }else{
                echo '<script type="text/javascript"> alert("Bandykite dar karta.") </script>';
             }
        }
?>
