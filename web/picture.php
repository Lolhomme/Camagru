<?php
require ('./includes/dbConnect.php');
session_start();

if (isset($_SESSION['user'])) {
    if (empty($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0)
        header('location: index.php');

    $pictures_id = $_GET['id'];
    $users_id = $_SESSION['user']['id'];

    /*Get author username and id picture*/
    $req = $db->prepare("select users.mail, users.id from users join pictures on users.id=pictures.users_id where pictures.id=:pictures_id");
    $req->bindValue(':pictures_id', $pictures_id);
    if ($req->execute() && $row = $req->fetch())
        $author = $row;

    /*Like ID + Déjà liké*/
    $req = $db->prepare("select id from .like where (pictures_id=:pictures_id) and (users_id=:users_id)");
    $req->bindValue(':pictures_id', $pictures_id);
    $req->bindValue(':users_id', $users_id);
    if ($req->execute() && $row = $req->fetch()) {
        $likeId = $row['id'];
        $isLiked = true;
    }

    if (!empty($_POST['picId'])) {
        if (isset($isLiked)) { /*Unlike button*/
            $req = $db->prepare("delete from .like where id=:id");
            $req->bindValue(':id', $likeId);
            $req->execute();
        } else { /*Like button*/
            $req = $db->prepare("INSERT INTO .like (users_id, pictures_id) VALUES (:users_id, :pictures_id)");
            $req->bindValue(':users_id', $users_id, \PDO::PARAM_INT);
            $req->bindValue(':pictures_id', $pictures_id, \PDO::PARAM_INT);
            $req->execute();
        }
    }

    if (!empty($_POST['textCom'])) {
        $content = htmlspecialchars($_POST['textCom']);

        /*Comment*/
        $req = $db->prepare("insert into comment (users_id, pictures_id, content) values (:users_id, :pictures_id, :content)");
        $req->bindValue('users_id', $users_id);
        $req->bindValue(':pictures_id', $pictures_id);
        $req->bindValue(':content', $content);
        if ($req->execute()) {
            $destinataire = $author['mail'];

            $subject = "Nouveau commentaire";
            $header = "De Camagru tête de cul";
            $message = 'Bonjour du gland,
    
     Un membre vient de commenter votre photo :
      
     ' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/picture.php?id=' . $pictures_id . '
     
     -----------------------
     Ceci est un email automatique, veuillez ne pas y répondre.';
            mail($destinataire, $subject, $header, $message);
        }
    }

    if (!empty($_POST['delPic'])){
        if ($author['id'] = $users_id)
            
    }

    /*Get comments datetime and username*/
    $req = $db->prepare("SELECT 
                comment.content, 
                comment.created_at, 
                users.id, 
                users.username
            FROM comment
            JOIN users ON users.id=comment.users_id
            WHERE comment.pictures_id=:pictures_id
            ORDER BY comment.created_at");
    $req->bindValue(':pictures_id', $pictures_id);
    if ($req->execute() && $row = $req->fetchAll())
        $comments = $row;

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
        <div class="col-xs-12 col-md-8-nogutter photo">
            <img src="img/uploads/<?=$pictures_id?>.png">
        </div>
        <div class="col-xs-12 col-md-4 comment">
            <form action="" method="post">
                <input type="text" name="textCom">
                <button type="submit" id="sendCom">Poster votre commentaire</button>
            </form>
            <?php foreach ($comments as $comment):?>
            <p><?=htmlspecialchars($comment['content']);?></p>
            <p>Posté par <?=$comment['username'];?> le : <?=$comment['created_at'];?></p>
            <? endforeach;?>
        </div>
        <div class="col-xs-12 col-md-1-nogutter like">
            <form action="picture.php?id=<?=$pictures_id?>" method="post" id="toLike" name="toLike">
                <input type="hidden" id="img-d" name="picId" value="<?=$pictures_id?>">
                <button id="likeBts"></button>
                <p id="likeNbr"><?=number_format($NbrLikes);?></p>
            </form>
        </div>
        <div class="col-xs-12 col-md-2-nogutter delete">
            <form action="picture.php?id=<?=$pictures_id?>" method="post">
                <input name="delPic" type="hidden">
                <button type="submit" id="delImg">Supprimer votre ganache</button>
            </form>
        </div>
    </div>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>