<?php
require ('./includes/dbConnect.php');
session_start();

if (isset($_SESSION['user'])){
    if (empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0)
        header('location: index.php');

    else{

        /*Like button*/
        $pictures_id = $_GET['id'];
        $req = $db->prepare('INSERT INTO .like (users_id, pictures_id) VALUES (:users_id, pictures_id)');
        $req->bindValue(':users_id', $_SESSION['id'], \PDO::PARAM_INT);
        $req->bindValue(':pictures_id', $pictures_id, \PDO::PARAM_INT);
        if ($req->execute())
            echo 'Success';
        /*Nombre de like*/
        $req = $db->prepare('select count(*) from .like where (pictures_id=:pictures_id');
        $req->bindValue(':pictures_id', $pictures_id);
        if ($req->execute() && $row = $req->fetchColumn())
            $NbrLikes = $row;

        /*Deja liké*/
        $req = $db->prepare('select count (*) from .like where (pictures_id=:pictures_id) and (users_id=:users_id)');
        $req = array(':pictures_id', $pictures_id,
                     ':users_id', $_SESSION['id']);
        if ($req->execute() && $row = $req->fetchColumn())
            $isLiked = $row;
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
            <button id="likeBts"></button>
            <input type="hidden" id="img-d" value="">
        </div>
        <p id="likeNbr"<?=number_format($NbrLikes);?>></p>
    </div>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>