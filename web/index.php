<?php
require("./includes/dbConnect.php");

session_start();

if (isset($_SESSION['logged']))
	include ("./includes/home.php");
else
	include("./includes/login.php");
?>