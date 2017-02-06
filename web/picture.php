<?php
require ('./includes/dbConnect.php');
session_start();

if (isset($_SESSION['user'])){
    if (empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0)
        header('location: index.php');

    $pictures_id = $_GET['id'];

    /*Like ID + Déjà liké*/
    $req = $db->prepare("select id from .like where (pictures_id=:pictures_id) and (users_id=:users_id)");
    $req->bindValue(':pictures_id', $pictures_id);
    $req->bindValue(':users_id', $_SESSION['user']['id']);
    if ($req->execute() && $row = $req->fetch()) {
        $likeId = $row['id'];
        $isLiked = true;
    }

    if (!empty($_POST)){
        if (isset($isLiked)){ /*Unlike button*/
            $req = $db->prepare("delete from .like where id=:id");
            $req->bindValue(':id', $likeId);
            $req->execute();
        }
        else { /*Like button*/
            $req = $db->prepare("INSERT INTO .like (users_id, pictures_id) VALUES (:users_id, :pictures_id)");
            $req->bindValue(':users_id', $_SESSION['user']['id'], \PDO::PARAM_INT);
            $req->bindValue(':pictures_id', $pictures_id, \PDO::PARAM_INT);
            $req->execute();
        }
    }

    /*Nombre de like*/
    $req = $db->prepare("select count(*) from .like where (pictures_id=:pictures_id)");
    $req->bindValue(':pictures_id', $pictures_id);
    if ($req->execute() && $row = $req->fetchColumn())
        $NbrLikes = $row;
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
            <img src="img/uploads/<?=$pictures_id?>.png">
        </div>
        <div class="col-xs-12 like">
            <form action="picture.php?id=<?=$pictures_id?>" method="post" id="toLike" name="toLike">
                <input type="hidden" id="img-d" name="picId" value="<?=$pictures_id?>">
                    <button id="likeBts"></button>
                <p id="likeNbr"><?=number_format($NbrLikes);?></p>
            </form>
        </div>
    </div>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>