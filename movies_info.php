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
    <div id='div-info'>
        <h1><?php echo $row['pavadinimas'] ?></h1>
        <img src='uploads/<?php echo $row['paveiksliukas'] ?>'>
    </div>
    
	</body>
</html> 