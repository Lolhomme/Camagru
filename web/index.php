<?php
require("./includes/dbConnect.php");

session_start();

if (isset($_SESSION['logged']))
	include ("home.php");
else
	include("./includes/login.php");
?>