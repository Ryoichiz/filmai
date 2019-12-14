<?php
    session_start();
    include('includes/loadControl.php');
    $controller = new MainPageController();

    $pavadinimas = $_POST['pavadinimas'];
    $metai = $_POST['metai'];
    $trukme = $_POST['trukme'];
    $kaina = $_POST['kaina'];
    $anonsas = $_POST['anonsas'];
    $paveiksliukas = $_FILE['paveiksliukas'];

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

        $sql = "SELECT * FROM filmas";
        $result = mysqli_query($conn, $sql) or die ("Bad Querry: $sql");


?>
