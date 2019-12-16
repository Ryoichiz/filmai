<?php
if(isset($_GET['ID'])){
    session_start();
    include('includes/loadControl.php');
    $controller = new MainPageController();

        $conn = $controller->Connection();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $ID = mysqli_real_escape_string($conn, $_GET['ID']);
        $sql = "DELETE FROM filmas WHERE id='$ID'";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");
        header('Location: movies.php?Sekmingas ištrinimas');
    }else
    header('Location: movies.php');


?>