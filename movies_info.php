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
        $sql = "SELECT * FROM filmas WHERE id='$ID'";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");

        

        $sql1 = "SELECT zanrai.pavadinimas
        FROM `filmo_zanrai`
        JOIN filmas ON fk_filmo_id = filmas.id 
        JOIN zanras ON fk_zanro_id = zanras.id
        JOIN zanrai ON zanras.fk_zanras = zanrai.id
        WHERE filmas.id = '$ID' ";
        $result1 = mysqli_query($conn, $sql1) or die ("Bad Querry: $sql1");

        $datas = array();
        if(mysqli_num_rows($result1) > 0){
            while ($row1= mysqli_fetch_assoc($result1)){
                $datas[] = $row1;
            }
        }
        //var_dump($datas);

        $row = mysqli_fetch_array($result);
    }else
    header('Location: movies.php');


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
	<body>
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
    <?php
    if($_SESSION['role'] == 'Administratorius'){
        echo "<div class='insert-button'> <a href=\"movie_edit.php?ID=$ID\">Redaguoti</a> <a href=\"movie_delete.php?ID=$ID\">Ištrinti</a></div>";
    }
    ?>
    <div>
        <div class='pic_div'>
            <img src='uploads/<?php echo $row['paveiksliukas'] ?>' height="600" width="400">
        </div>
        <div id='div-info'>
            <h1><?php echo $row['pavadinimas'] ?></h1>
            <p><?php echo $row['aprasymas'] ?></p>
        </div>
        <div class='info_div'>
            <p>Trukmė: <?php echo $row['trukme'] ?></p>
            <p>Ivetinimas: <?php echo $row['ivertinimas'] ?></p>
            <p>Metai: <?php echo $row['isleidimo_metai'] ?></p>
            <p>Žanrai: <?php foreach ($datas as $data) {
                echo $data['pavadinimas']." ";
            } 
            ?></p>
            <p>Kaina: <?php echo $row['kaina'] ?> eur.</p>
        </div>
        <div class='anonsas'>
            <?php $videourl = str_replace("watch?v=", "embed/",$row['anonsas']); ?>
            <iframe width="420" height="315" src='<?php echo $videourl ?>' allowfullscreen> </iframe> 
        </div>
    </div>
	</body>
</html> 