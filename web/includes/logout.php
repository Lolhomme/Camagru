<?php
session_start();
$_SESSION = array();
session_destroy();
header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php');
?>