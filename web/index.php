<?php
require("./includes/dbConnect.php");

session_start();

if (isset($_SESSION['user']))
	include ("./includes/home.php");
else
	include("./includes/login.php");
?>