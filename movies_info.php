<?php
if(isset($_GET['ID'])){
	session_start();
	include('includes/loadControl.php');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 
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
        $conn->set_charset("utf8");

        $ID = mysqli_real_escape_string($conn, $_GET['ID']);
        $sql = "SELECT * FROM filmas WHERE id='$ID'";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");

        $movie_list = "SELECT filmu_sarasas.id, pavadinimas FROM `naudotojas_sarasas`
        JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
        JOIN naudotojas ON fk_naudotojas = naudotojas.id
        WHERE naudotojas.id = '$id'";
        $movies = mysqli_query($conn, $movie_list) or die ("Bad Querry: $sql1");
         /*if (mysqli_num_rows($movies) > 0)
        {
            while($row3 = $movies->fetch_assoc())
            {
                print_r($row3);
            }
        } */
        $sql1 = "SELECT zanrai.pavadinimas
        FROM `filmo_zanrai`
        JOIN filmas ON fk_filmo_id = filmas.id 
        JOIN zanras ON fk_zanro_id = zanras.id
        JOIN zanrai ON zanras.fk_zanras = zanrai.id
        WHERE filmas.id = '$ID' ";
        $result1 = mysqli_query($conn, $sql1) or die ("Bad Querry: $sql1");

        $sql_fetch_user_ratings = "SELECT ivertinimai.balas FROM ivertinimai WHERE ivertinimai.id = $ID";
        $result_user_ratings = mysqli_query($conn, $sql_fetch_user_ratings) or die ("Bad Querry: $sql_fetch_user_ratings");

        $rating_array = array();
        $user_rating = 0;

        while($rating = mysqli_fetch_array($result_user_ratings)){
            $rating_array[] = $rating['balas'];
        }

        if (count($rating_array) > 0)
        {
            foreach($rating_array as $rating_value){
                $user_rating += $rating_value;
            }
            $user_rating = $user_rating / count($rating_array);
        }elseif(count($rating_array) == 0){
            $user_rating = 'No user ratings found.';
        }

        $datas = array();
        if(mysqli_num_rows($result1) > 0){
            while ($row1= mysqli_fetch_assoc($result1)){
                $datas[] = $row1;
            }
        }
        //var_dump($datas);

        $row = mysqli_fetch_array($result);

        //IMDB ivertinimo gavimas

        $imdb_key = 'http://www.omdbapi.com/?apikey=ff402839';
        $imdb_title = '&t='. str_replace(' ','+', $row['pavadinimas']);
        $imdb_year = '&y=' . $row['isleidimo_metai'];
        $imdb_link = $imdb_key . $imdb_title . $imdb_year;

        $imdb_json = file_get_contents($imdb_link);
        $imdb_obj = json_decode($imdb_json);

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
    	<nav class="navbar navbar-expand-lg navbar-inverse bg-dark">
        <div class='container'>
            <?php
                MainController::printNavigationBar("movies.php");
            ?>
        </div>
    </nav>
    <table class='film_form'><tr><td>
         Atgal į [<a href="movies.php">Filmų Peržiūrą</a>]
      </td></tr>
    </table>
    <?php
    if($_SESSION['role'] == 'Administratorius'){
        echo "<div class='insert-button'> <a href=\"movie_discount.php?ID=$ID\">Nuolaida</a> <a href=\"movie_edit.php?ID=$ID\">Redaguoti</a> <a href=\"movie_delete.php?ID=$ID\">Ištrinti</a></div>";
    }   
    ?>
    <div>
        <div class='pic_div'>
            <img src='uploads/<?php echo $row['paveiksliukas'] ?>' height="600" width="400">
            <form action="" method="POST">
                <br>
                <label>Pridėk į sąraša!</label>
                <select name="list">
                <option value=""></option>
                <?php while($row2 = mysqli_fetch_array($movies)):;   ?>
                <option value=<?php echo "'".$row2[1]."' "; if(isset($_GET['pavadinimas'])) if($_GET['pavadinimas'] == $row2[1]) echo "selected" ?>><?php echo $row2[1];  ?> </option>
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
            <p>Vartotojų įvetinimas: <?php echo $user_rating ?> 
            <?php 
            if($_SESSION['role'] == 'Naudotojas' || $_SESSION['role'] == 'Administratorius'){
                ?>
                <form action="" method="post">
                    <select name='select_rating'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>
                        <option value='9'>9</option>
                        <option value='10'>10</option>
                    </select>
                    <button class='button' type='submit' name='Ivertinti'>Ivertinti</button>
                </form>
            <?php } ?>
            </p>
            <p>IMDB Įvetinimas: <?php echo $imdb_obj->imdbRating ?></p>
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
        <p></p>
        <div class='recenzijos' align="center">
            <form action="" method="POST">
                <textarea name="komentaras" rows="4" cols="50"></textarea>
                <button class='button' type='submit' name='Komentuoti'>Sukurti Recenzija</button>
            </form>
        </div>
        <div align="center" style="white-space:pre;">

        <?php
            $sql_fetch_reviews = "SELECT * FROM recenzija WHERE fk_filmas = $ID";
            $query_fetch_reviews = mysqli_query($conn,$sql_fetch_reviews);           
        ?>

        <table border="1" width="800">
            <?php
            while($review_row = mysqli_fetch_assoc($query_fetch_reviews))
            {
                $user_id = $review_row['fk_naudotojas'];
                $sql_fetch_name = "SELECT naudotojas.vardas FROM naudotojas WHERE id = $user_id";
                $query_fetch_name = mysqli_query($conn, $sql_fetch_name);
                $table_name = mysqli_fetch_assoc($query_fetch_name);


                $review_id = $review_row['id'];
                $sql_fetch_review_rating = "SELECT recenziju_vertinimas.ivertinimas FROM recenziju_vertinimas WHERE fk_recenzija = $review_id";
                $query_fetch_review_rating = mysqli_query($conn, $sql_fetch_review_rating);
                
                $review_rating_array = array();
                $review_sum = 0;

                while($rating_review = mysqli_fetch_array($query_fetch_review_rating)){
                    $review_rating_array[] = $rating_review['ivertinimas'];
                }

                if (count($review_rating_array) > 0)
                {
                    foreach($review_rating_array as $review_rating_value){
                        $review_sum += $review_rating_value;
                    }
                }elseif(count($rating_array) == 0){
                    $review_sum = 0;
                }

                echo "<tr>
                <td width='30%'>".$table_name['vardas']."  (".$review_row['sukurimo_data'].")\nRecenzija patiko: ".$review_sum."</td> 
                <td width='70%''>".$review_row['komentaras']."</td>
                </tr>";
            }
            ?>
        
            
        </table>

    </div>
	</body>
</html> 

<?php 

if(isset($_POST['Prideti'])){
        
        $name = $_POST['list'];

        $query2 = "SELECT filmu_sarasas.id, pavadinimas FROM `naudotojas_sarasas`
        JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
        JOIN naudotojas ON fk_naudotojas = naudotojas.id
        WHERE pavadinimas='$name'";
        $q2result = mysqli_query($conn, $query2) or die ("Bad Querry: $query2");
        $q2row = mysqli_fetch_array($q2result);

        $query3 = "SELECT COUNT(pavadinimas) FROM `sarasas_filmas` 
            JOIN filmu_sarasas ON fk_sarasas = filmu_sarasas.id
            WHERE pavadinimas = '$name' AND fk_filmas = '$ID'";
        $q3result = mysqli_query($conn, $query3) or die ("Bad Querry: $query3");
        $row5 = mysqli_fetch_array($q3result);


        if($row5['COUNT(pavadinimas)'] != 0){
            echo '<script type="text/javascript"> alert("Jau turite šį filma sąraše.") </script>';
        }else{

            $list_id = $q2row['id'];
            $query = "INSERT INTO sarasas_filmas (fk_sarasas, fk_filmas)
                VALUES ('$list_id','$ID')";
            //$q1result = mysqli_query($conn, $query) or die ("Bad Querry: $query");

            $query_run1 =mysqli_query($conn,$query);
            if($query_run1){
                echo '<script type="text/javascript"> alert("Sekmingai pridėta į sąraša'.' '.$name.' '.'!") </script>';
             }else{
                echo '<script type="text/javascript"> alert("Bandykite dar karta.") </script>';
             }

        }

    }

if(isset($_POST['Ivertinti'])){
    $selected_rating = $_POST['select_rating'];
    $sql_check = "SELECT ivertinimai.id FROM `ivertinimai` WHERE ivertinimai.fk_naudotojas = $id AND ivertinimai.id=$ID";
    $reslut_check = mysqli_query($conn,$sql_check);
    $check_array = array();

    while($check = mysqli_fetch_array($reslut_check)){
        $check_array[] = $check['id'];
    }

    if(count($check_array) == 0){
        $sql_insert_user_rating = "INSERT INTO ivertinimai (id, fk_naudotojas, balas) VALUES ('$ID','$id','$selected_rating')";
        $query_insert_user_rating = mysqli_query($conn,$sql_insert_user_rating);
    }elseif(count($check_array) > 0){
        $sql_update_rating = "UPDATE ivertinimai SET balas = $selected_rating WHERE fk_naudotojas = $id AND ivertinimai.id=$ID";
        $query_update_rating = mysqli_query($conn,$sql_update_rating);
    }
}

if(isset($_POST['Komentuoti'])){
    if($_SESSION['role'] == 'Naudotojas' || $_SESSION['role'] == 'Administratorius'){
        $insert_text = $_POST['komentaras'];
        $insert_date = date("Y-m-d");
        if($insert_text != null){
            $sql_insert_review = "INSERT INTO recenzija (komentaras, sukurimo_data, fk_naudotojas, fk_filmas) VALUES ('$insert_text', '$insert_date', $id, $ID)";
            $query_insert_review = mysqli_query($conn,$sql_insert_review);
        }
    }
}
?>