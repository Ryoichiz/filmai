<?php
	session_start();
	include('includes/loadControl.php');
	$controller = new MainPageController();

        $conn = $controller->Connection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM filmas";
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
    	MainController::printTopBar("movies.php");
    	?>
    	<nav class="navbar navbar-expand-lg navbar-inverse bg-dark">
        <div class='container'>
            <?php
                MainController::printNavigationBar("movies.php");
            ?>
        </div>
    </nav>
    <?php
    if($_SESSION['role'] == 'Administratorius'){
    	echo "<div class='insert-button'> <a href=\"movie_form.php\">Naujas filmas</a></div>";
	}
	?>


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
            echo "<h2>No Titles to display</h2>";
        }
     ?>
 	</div>
	</body>
</html> 