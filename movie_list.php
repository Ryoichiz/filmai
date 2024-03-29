<?php
	session_start();
	include('includes/loadControl.php');
	$controller = new MainPageController();
    $id = $_SESSION['id'];

        $conn = $controller->Connection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");

        $movie_list = "SELECT filmu_sarasas.id, pavadinimas FROM `naudotojas_sarasas`
        JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
        JOIN naudotojas ON fk_naudotojas = naudotojas.id
        WHERE naudotojas.id = '$id'";
        $movies = mysqli_query($conn, $movie_list) or die ("Bad Querry: $sql1");

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
    	<nav class="navbar navbar-expand-lg navbar-inverse bg-dark">
        <div class='container'>
            <?php
                MainController::printNavigationBar("profile.php");
            ?>
        </div>
    </nav>
    <table class='film_form'><tr><td>
         Atgal į [<a href="profile.php">Profilį</a>]
      </td></tr>
    </table>
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
    <?php
        if (mysqli_num_rows($movies) > 0)
        {
            while($row = $movies->fetch_assoc())
            {
                echo "<div class='film_form'><a href='list_info.php?ID={$row['id']}''><h5>{$row['pavadinimas']}</h5></a></div>";
                echo '<div class=\'margin\'><form action="" method="POST"><input type="hidden" name="sarasoid" value="'.$row['id'].'"><button class="delete_button" type="submit" name="Trinti">Trinti</button></form></div><br>';
            }
        }else {
            echo "<h2>No Titles to display</h2>";
        }
     ?>
	</body>
</html> 

<?php 

if(isset($_POST['Pateikti'])){

    $name = $_POST['pavadinimas'];

    $check = "SELECT COUNT(id) FROM filmu_sarasas WHERE pavadinimas = '$name'";
    $checkrez = mysqli_query($conn, $check) or die ("Bad Querry: $check");
    $checkrow = mysqli_fetch_array($checkrez);
    
    if($checkrow['COUNT(id)'] != 0){
            echo '<script type="text/javascript"> alert("Yra jau toks sąrašas.") </script>';
        }else{

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
                echo '<script type="text/javascript"> alert("Sukurtas nauajs sąrašas!.") </script>';
                echo '<meta http-equiv="refresh" content="0; url=movie_list.php" />';
             }else{
                echo '<script type="text/javascript"> alert("Nepavyko sukurti") </script>';
             }
        }
}

if(isset($_POST['Trinti'])){

    $saraso_id = $_POST['sarasoid'];
    echo $saraso_id;

    $querydelete = "DELETE FROM filmu_sarasas WHERE id = '$saraso_id'";
    $resultqu = mysqli_query($conn, $querydelete) or die ("Bad Querry: $querydelete");
    $query_run2 =mysqli_query($conn,$querydelete);
            if($query_run2){
                echo '<script type="text/javascript"> alert("Sąrašas ištrintas") </script>';
                echo '<meta http-equiv="refresh" content="0; url=movie_list.php" />';
             }else{
                echo '<script type="text/javascript"> alert("Bandykite dar karta.") </script>';
             }
}
?>