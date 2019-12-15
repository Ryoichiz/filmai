<?php
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
	<body style="background-color:whitesmoke">
		<?php
    	MainController::printTopBar("movie_list.php");
    	?>
    	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class='container'>
            <?php
                MainController::printNavigationBar("profile.php");
            ?>
        </div>
    </nav>
    <div class='film_form'>
        <form action="" method="POST">
            <div>
            <p>Sąrašo pavadinimas</p>
            <input type="text" class="form-control" size="40" name="pavadinimas" required >
            </div class="form-group">
            <br>
            <button class='button' type="submit" name="Pateikti">Pateikti</button>  
        </form>     
    </div>
    <div class='film_form'>
        <h3>Jūsų sukurti filmų sarašai:</h3>
    </div>
    
	</body>
</html> 

<?php 

if(isset($_POST['Pateikti'])){

    $name = $_POST['pavadinimas'];
    echo $name;

    $query = "INSERT INTO filmu_sarasas (pavadinimas)
            VALUES ('$name')";
    $q1result = mysqli_query($conn, $query) or die ("Bad Querry: $query");

    $query1 = "SELECT id FROM filmu_sarasas WHERE pavadinimas = '$name'";
    $q1result = mysqli_query($conn, $query1) or die ("Bad Querry: $query1");

    $q1row = mysqli_fetch_array($q1result);

    $list_id = $q1row['id'];
    $query2 = "INSERT INTO naudotojas_sarasas (fk_naudotojas, fk_sarasas)
            VALUES ('$id', '$list_id')";
    //$q2result = mysqli_query($conn, $query2) or die ("Bad Querry: $query2");
        $query_run =mysqli_query($conn,$query2);
            if($query_run){
                echo '<script type="text/javascript"> alert("Sukurtas nauajs sąrašas!") </script>';
             }else{
                echo '<script type="text/javascript"> alert("Nepavyko sukurti") </script>';
             }

}
?>