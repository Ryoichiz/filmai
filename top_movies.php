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

        $sql = "SELECT * FROM filmas ORDER BY ivertinimas DESC LIMIT 10";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");


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
    	<nav class="navbar navbar-expand-lg navbar-inverse bg-dark">
        <div class='container'>
            <?php
                MainController::printNavigationBar("top_movies.php");
            ?>
        </div>
    </nav>

    <div class='film_form'>
    <?php
    	if (mysqli_num_rows($result) > 0)
        {
            $count = 0;
            while($row = $result->fetch_assoc())
            {
                $count++;
                echo '<div class=\'top\'><img src="uploads/'.$row['paveiksliukas'].'" height="300" width="200">';
                echo " {$count}.<a href='movies_info.php?ID={$row['id']}'>{$row['pavadinimas']} ({$row['isleidimo_metai']})</a> <label>Ivertinimas: {$row['ivertinimas']} </label></div><br>";
            }
        }else {
            echo "<h2>No Titles to display</h2>";
        }
     ?>
 	</div>
	</body>
</html> 