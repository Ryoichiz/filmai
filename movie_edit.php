<?php
    if(isset($_GET['ID'])){
    session_start();
    include('includes/loadControl.php');
    $controller = new MainPageController();

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
        }

        $ID = mysqli_real_escape_string($conn, $_GET['ID']);
        $sql = "SELECT * FROM filmas WHERE id ='$ID'";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");
        $row = mysqli_fetch_array($result);
        $apr = $row['aprasymas'];
    }else{
        header('Location: movies.php?Nera tokio id');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Required Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="./styles/stylesheet.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body style='background-color: whitesmoke'>
        <?php
        MainController::printTopBar("movies.php");
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class='container'>
            <?php
                MainController::printNavigationBar("movies.php");
            ?>
        </div>
    </nav>
    <div class='film_form'>
        <form action="" method="POST" enctype="multipart/form-data">
            <p>Filmo pavadinimas</p>
            <input type="text" size="40" name="pavadinimas" value="<?php echo $row['pavadinimas']; ?>" required >
            <br>
            <br>
            <label>Pasirinkite filmo paveiksliuką</label>
            <br>
            <!-- initially hide image tag -->
            <img src='uploads/<?php echo $row['paveiksliukas'] ?>' height="200" id="image">
            <!-- passing this explicitly -->
            <input type="file" name="paveiksliukas"  onchange="showImage.call(this)" required>
            <script>
                function showImage(){
                    if(this.files && this.files[0]){
                        var obj = new FileReader();
                        obj.onload = function(data){
                            var image = document.getElementById("image");
                            image.src = data.target.result;
                            image.style.display = "block";
                        }
                        obj.readAsDataURL(this.files[0]);
                    }
                }

            </script>>
            <br>
            <br>
            <p>Įveskite išleidimo metus</p>
            <input type="number"  name="metai" value="<?php echo $row['isleidimo_metai']; ?>" min="0" >m.
            <br>
            <br>
            <p>Įveskite filmo trukmę minutemis</p>
            <input type="number"  name="trukme" value="<?php echo $row['trukme']; ?>" min="0" max="300" >min.
            <br>
            <br>
            <p>Filmo aprašymas</p>
            <textarea name="aprasymas" cols="118" rows="5" ><?php echo $apr ?></textarea>
            <br>
            <br>
            <p>Įveskite filmo kainą</p>
            <input type="text"  name="kaina" value="<?php echo $row['kaina']; ?>" min="0" pattern="[0-9]+([\.][0-9]{0,2})?" >eur.
            <br>
            <br>
            <p>Nuorada iš Youtube platformos</p>
            <input type="text" size="45" name="anonsas" value="<?php echo $row['anonsas']; ?>" 
             pattern="^(https:\/\/)?www.youtube.com\/watch\?v=(\w)+$" required>
            <br>
            <br>
            <button class='button' type="submit" name="Pateikti">Pateikti</button>  
        </form>
    </div>
    </body>
</html> 

<?php 
    if(isset($_POST['Pateikti'])){
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

        $query = "UPDATE filmas SET pavadinimas='$pavadinimas',isleidimo_metai='$metai',trukme='$trukme',aprasymas='$aprasymas',kaina='$kaina',paveiksliukas='$fileNameNew',anonsas='$anonsas' WHERE id='$ID'";

        $query_run =mysqli_query($conn,$query);
            if($query_run){
                echo '<script type="text/javascript"> alert("Data Updated") </script>';
             }else{
                 die ("Klaida įrašant:" .mysqli_error($conn));
             }
    }
?>