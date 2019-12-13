<?php
	session_start();
	include('includes/loadControl.php');
	$controller = new MainPageController();
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Required Meta Tags -->
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	    <!-- Bootstrap CSS -->
	    <link rel="stylesheet" href="./style/stylesheet.css">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<?php
    	MainController::printTopBar("index.php");
    	?>
    	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class='container'>
            <?php
                MainController::printNavigationBar("index.php");
            ?>
        </div>
    </nav>

	</body>
</html> 