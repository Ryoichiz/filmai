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

        $conn = $controller->Connection();

        $ID = mysqli_real_escape_string($conn, $_GET['ID']);
        

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            $query = "UPDATE filmas SET pavadinimas='$pavadinimas',isleidimo_metai='$metai',trukme='$trukme',aprasymas='$aprasymas',kaina='$kaina',paveiksliukas='$fileNameNew',anonsas='$anonsas' WHERE id='$ID'";
        }
            $query_run =mysqli_query($conn,$query);
            if($query_run){
                echo '<script type="text/javascript"> alert("Data Updated") </script>';
             }else{
                 echo '<script type="text/javascript"> alert("Error") </script>';
             }

?>