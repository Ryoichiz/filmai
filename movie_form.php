<?php
    session_start();
    include('includes/loadControl.php');
    $controller = new MainPageController();

        $conn = $controller->Connection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");

        $sql = "SELECT * FROM filmas";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");
        $row = mysqli_fetch_array($result);
        $apr = $row['aprasymas'];

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
    <body style='background-color: whitesmoke'>
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
    <div class='film_form'>
        <form action="insert_movie.php" method="POST" enctype="multipart/form-data">
            <p>Filmo pavadinimas</p>
            <input type="text" size="40" name="pavadinimas" placeholder="filmo pavadinimas" required>
            <br>
            <br>
            <label>Pasirinkite filmo paveiksliuką</label>
            <br>
            <!-- initially hide image tag -->
            <img src='uploads/<?php echo $row['paveiksliukas'] ?>' height="200" id="image">
            <!-- passing this explicitly -->
            <input type="file" name="paveiksliukas"  onchange="showImage.call(this)" required>
            <script>
                function showImage(){
                    if(this.files && this.files[0]){
                        var obj = new FileReader();
                        obj.onload = function(data){
                            var image = document.getElementById("image");
                            image.src = data.target.result;
                            image.style.display = "block";
                        }
                        obj.readAsDataURL(this.files[0]);
                    }
                }

            </script>
            <br>
            <br>
            <p>Įveskite išleidimo metus</p>
            <input type="number"  name="metai" placeholder="pav. 2012" min="0" required>m.
            <br>
            <br>
            <p>Įveskite filmo trukmę minutemis</p>
            <input type="number"  name="trukme" placeholder="pav. 120" min="0" max="300" required>min.
            <br>
            <br>
            <p>Filmo aprašymas</p>
            <textarea name="aprasymas" cols="118" rows="5" required></textarea>
            <br>
            <br>
            <p>Įveskite filmo kainą</p>
            <input type="text"  name="kaina" placeholder="pav. 24.40" min="0" pattern="[0-9]+([\.][0-9]{0,2})?" required>eur.
            <br>
            <br>
            <p>Nuorada iš Youtube platformos</p>
            <input type="text" size="45" name="anonsas" placeholder="pav. https://www.youtube.com/watch?v=JaRq73y5MJk" 
             pattern="^(https:\/\/)?www.youtube.com\/watch\?v=([a-zA-Z0-9!@#$%^&*\_-+=])+$" required>
            <br>
            <br>
            <button class='button' type="submit" name="Pateikti">Pateikti</button>
            
        </form>
    </div>
    </body>
</html> 