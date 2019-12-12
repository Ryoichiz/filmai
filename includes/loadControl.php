<?php
include_once("includes/MainController.php");
include_once("includes/BasicController.php");

//include_once("includes/AdminController.php");

foreach (glob("includes/*.php") as $filename)
{
    include_once $filename;
}