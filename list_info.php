<?php
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
        $sql = "SELECT filmas.id, filmas.pavadinimas, filmas.paveiksliukas FROM `sarasas_filmas` 
            JOIN filmas ON fk_filmas = filmas.id
            JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
            WHERE filmu_sarasas.id = '$ID'";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");
        //$row9 = mysqli_fetch_array($result);
        //$filmo_id = $row9['id'];

        /*
        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                print_r($row);
            }
        } */

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
    	MainController::printTopBar("list_movie.php");
    	?>
    	<nav class="navbar navbar-expand-lg navbar-inverse bg-dark">
        <div class='container'>
            <?php
                MainController::printNavigationBar("profile.php");
            ?>
        </div>
    </nav>
    <table class='film_form'><tr><td>
         Atgal į [<a href="movie_list.php">Filmų sąrasą</a>]
      </td></tr>
    </table>

    <div>
    <?php
        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                echo '<div class=\'film_form\'><img src="uploads/'.$row['paveiksliukas'].'" height="120" width="80">';
                echo " <a href='movies_info.php?ID={$row['id']}'>{$row['pavadinimas']}</a></div><br>";
                echo '<div class=\'margin\'><form action="" method="POST"><input type="hidden" name="filmoid" value="'.$row['id'].'"><button class="delete_button" type="submit" name="Trinti">Trinti</button></form></div><br>';
            }
        }else {
            echo "<h2>Neturite šiame sąraše jokių filmų</h2>";
        }
     ?>
    </div>
	</body>
</html> 
<?php 
if(isset($_POST['Trinti'])){

    $filmo_id = $_POST['filmoid'];
    //echo $filmo_id;

    $query = "DELETE FROM sarasas_filmas WHERE fk_filmas = '$filmo_id' AND fk_sarasas = '$ID'";
    //$result = mysqli_query($conn, $query) or die ("Bad Querry: $query");
    $query_run1 =mysqli_query($conn,$query);
            if($query_run1){
                echo '<script type="text/javascript"> alert("Filmas ištrintas") </script>';
                echo '<meta http-equiv="refresh" content="0; url=list_info.php?ID='.$ID.'" />';
                echo '"'.$filmo_id.'"';
             }else{
                echo '<script type="text/javascript"> alert("Bandykite dar karta.") </script>';
             }
}

?>