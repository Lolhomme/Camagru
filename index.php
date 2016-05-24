<?php session_start();
		include 'login_exist.php';
		if (!$_SESSION['loggued_on_user'] || $_SESSION['loggued_on_user'] == "")
			$_SESSION["guest"] = "OK";
		if ($_GET[user])
		{
			$tab = unserialize(file_get_contents("config/database.php"));
			$i = login_exist($tab, $_GET[user]);
			if ($i != -1)
				file_put_contents("config/database.php", serialize($tab));
		}
		if ($_SESSION["guest"] == "OK")
		{

		}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Camagru</title>
		<link rel="stylesheet" type="text/css" href="css/general.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
	</head>
	<body>
		<div id="header">
			<div id="logo">
				<h1><a href="index.php">Camagru</a></h1>
			</div>

		</div>

	</body>
</html>
