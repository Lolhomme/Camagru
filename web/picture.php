<?php
require ('./includes/dbConnect.php');
require ('includes/anti-csrf.php');
session_start();

$token = create_token();
$errors = array();
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
        if (check_token(1800, $_SERVER["REQUEST_SCHEME"] .'://' .$_SERVER["HTTP_HOST"].'/picture.php?id=' . $pictures_id)) {/*LIKE*/
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
        else
            $errors['token'] = true;
    }

    if (!empty($_POST['textCom']) && is_string($_POST['textCom'])) {
        if (check_token(1800, $_SERVER["REQUEST_SCHEME"] .'://' .$_SERVER["HTTP_HOST"].'/picture.php?id=' . $pictures_id)) { /*COMMENT*/
            $content = $_POST['textCom'];

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
        else
            $errors['token'] = true;
    }

    if (!empty($_POST['delPic'])){
       if (check_token(1800, $_SERVER["REQUEST_SCHEME"] .'://' .$_SERVER["HTTP_HOST"].'/picture.php?id=' . $pictures_id)) { /*DELETE*/
           if ($author['id'] = $users_id) {
               $req = $db->prepare("delete from pictures where id=:id");
               $req->bindValue(':id', $pictures_id);
               if ($req->execute()) {
                   if (file_exists('./img/uploads/' . $pictures_id . '.png')) {
                       unlink('./img/uploads/' . $pictures_id . '.png');
                       header('location: gallery.php');
                   }
               }
           }
       }
       else
           $errors['token'] = true;
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
else
    header('location: index.php');
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/html">
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
        <div class="col-xs-12">
            <a id="logout" href="./includes/logout.php">Se deconnecter</a>
            <a id="home" href="index.php">Accueil</a>
            <a id="gallery" href="gallery.php">Galerie</a>
        </div>
    </div>
    <div class="row" id="errors">
        <?php
        echo '<div class="col-xs-12 col-sm-4 col-sm-push-4">';
        if (isset($errors['token']))
            echo "<h4>Erreur dans le formulaire ou session expirée.</h4>";
        echo '</div>';
        ?>
    </div>
    <div class="row picture">
        <div class="col-xs-12-nogutter col-sm-12 col-md-9 photo" id="pic">
            <img src="img/uploads/<?=$pictures_id?>.png" style="height: auto; width: 100%">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 like">
            <form action="picture.php?id=<?=$pictures_id?>" method="post" id="toLike" name="toLike">
                <input type="hidden" id="img-d" name="picId" value="<?=$pictures_id?>">
                <input type="hidden" name="token" id="token" value="<?echo $_SESSION['token']?>">
                    <button class="fa likebt" id="likebts"></button>  <?=number_format($NbrLikes);?>
            </form>
        </div>
        <div class="col-xs-12-nogutter col-sm-12 col-md-9 comment">
            <form  method="post" id="come">
                <input placeholder="Laissez une trace..." type="text" name="textCom" id="com" class="col-xs-12-nogutter">
                <input type="hidden" name="token" id="token" value="<?echo $_SESSION['token']?>">
                <button type="submit" id="sendCom">Poster votre commentaire</button>
            </form>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 displayCom">
            <?php
            if (isset($comments))
                foreach ($comments as $comment):?>
                    <p><span style="color: #004C96"><?=$comment['username'];?></span> le : <span style="color: #0e84b5"><?=$comment['created_at'];?></span> : <?=htmlspecialchars($comment['content']);?></p>
                <? endforeach;?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 delete">
            <form method="post">
                <input name="delPic" type="hidden" value="<?=$pictures_id?>">
                <input type="hidden" name="token" id="token" value="<?echo $_SESSION['token']?>">
                <button type="submit" id="delImg">Supprimer votre ganache</button>
            </form>
        </div>
    </div>
</div>
<div class="col-xs-12" style="height: 200px"></div>
</body>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>