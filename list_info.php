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
    	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
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
    <div id='div-names'>
    <?php
        if (mysqli_num_rows($result) > 0)
        {
            while($row = $result->fetch_assoc())
            {
                echo '<div id=\'inline\'><center><img src="uploads/'.$row['paveiksliukas'].'" height="300" width="200"></center><br>';
                echo "<center><a href='movies_info.php?ID={$row['id']}'>{$row['pavadinimas']}</a><center> </div>";
            }
        }else {
            echo "<h2>Neturite šiame sąraše jokių filmų</h2>";
        }
     ?>
    </div>
	</body>
</html> 