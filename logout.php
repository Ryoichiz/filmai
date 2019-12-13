<?php

session_start();
include('includes/loadControl.php');

$model = new Model();

$model->logoutMe();
echo '<meta http-equiv="refresh" content="0; url=index.php" />';