<?php
if(isset($_GET['ID'])){
	session_start();
	include('includes/loadControl.php');
	$controller = new MainPageController();
    $id = $_SESSION['id'];
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

        $movie_list = "SELECT pavadinimas FROM `naudotojas_sarasas`
        JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
        JOIN naudotojas ON fk_naudotojas = naudotojas.id
        WHERE naudotojas.id = '$id'";
        $movies = mysqli_query($conn, $movie_list) or die ("Bad Querry: $sql1");

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

        $discount = $row['fk_nuolaida'];
        $sql2 = "SELECT * FROM kodo_kurimas WHERE id='$discount'";
        $result2 = mysqli_query($conn, $sql2) or die ("Bad Querry: $sql2");
        $row2 = mysqli_fetch_array($result2);
        $procent = $row2['procentas'];
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
        echo "<div class='insert-button'> <a href=\"movie_discount.php?ID=$ID\">Nuolaida</a> <a href=\"movie_edit.php?ID=$ID\">Redaguoti</a> <a href=\"movie_delete.php?ID=$ID\">Ištrinti</a></div>";
    }   
    ?>
    <div>
        <div class='pic_div' action="" method="POST">
            <img src='uploads/<?php echo $row['paveiksliukas'] ?>' height="600" width="400">
            <form>
                <br>
                <label>Pridėk į sąraša!</label>
                <select name="list">
                <option value=""></option>
                <?php while($row2 = mysqli_fetch_array($movies)):;   ?>
                <option value=<?php echo "'".$row2[0]."' "; if(isset($_GET['pavadinimas'])) if($_GET['pavadinimas'] == $row2[0]) echo "selected" ?>><?php echo $row2[0];  ?> </option>
            <?php endwhile; ?>
        </select>
                <button class='button' type="submit" name="Prideti">Pridėti</button>
            </form>
        </div>
        <div id='div-info'>
            <h1><?php echo $row['pavadinimas'] ?></h1>
            <p><?php echo $row['aprasymas'] ?></p>
        </div>
        <div class='info_div'>
            <p>Trukmė: <?php echo $row['trukme'] ?></p>
            <p>Įvetinimas: <?php echo $row['ivertinimas'] ?></p>
            <p>Metai: <?php echo $row['isleidimo_metai'] ?></p>
            <p>Žanrai: <?php foreach ($datas as $data) {
                echo $data['pavadinimas']." ";
            } 
            ?></p>
            <p>Kaina: <?php echo ($row['kaina'] - (($procent*$row['kaina'])/100)) ?> eur.</p>
        </div>
        <div class='anonsas'>
            <?php $videourl = str_replace("watch?v=", "embed/",$row['anonsas']); ?>
            <iframe width="420" height="315" src='<?php echo $videourl ?>' allowfullscreen> </iframe> 
        </div>
    </div>
	</body>
</html> 
<?php 

?>