<?php
session_start();

if (isset($_SESSION['user'])){
    if (isset($_GET['id'])){
        $pictureId = $_GET['id'];
    }
}
?>
<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru-Gallery</title>
    <link href="css/grid.css" type="text/css" rel="stylesheet">
    <link href="css/picture.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Galerie</h1>
    <div class="row nav">
        <div class="col-xs-12 col-sm-12">
            <a id="logout" href="./includes/logout.php">Se deconnecter</a>
            <a id="home" href="index.php">Accueil</a>
            <a id="gallery" href="gallery.php">Galerie</a>
        </div>
    </div>
    <div class="row picture">
        <div class="col-xs-12 photo">
            <img src="img/uploads/<?=$pictureId?>.png">
        </div>
        <div class="col-xs-12 like">
            <button id="likeBts"></button>145
            <input type="hidden" id="img-d" value="">
        </div>
    </div>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>