<?php
require ('./includes/dbConnect.php');
session_start();

if (isset($_SESSION)) {

    /*Pagination*/
    $req = $db->prepare('select count(id) from pictures');
    $req->execute();
    $row = $req->fetchColumn();
    $nbrPerPage = 12;
    $allPage = ceil($row / $nbrPerPage);
    if (isset($_GET['p']) && ($_GET['p']>0 && $_GET['p']<=$allPage))
        $firstPage = $_GET['p'];
    else
        $firstPage = 1;
    $cPage = (($firstPage - 1) * $nbrPerPage);

    /*Get all photo by datetime*/
    $req = $db->prepare("select id from pictures ORDER BY created_at DESC LIMIT $cPage,$nbrPerPage");
    if ($req->execute() && $row = $req->fetchAll())
        $photos = $row;
    else
        $noUploads = true;
}
?>
<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru-Gallery</title>
    <link href="css/grid.css" type="text/css" rel="stylesheet">
    <link href="css/gallery.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Galerie</h1>
    <div class="row nav">
        <div class="col-xs-12 col-sm-12">
            <a id="logout" href="./includes/logout.php">Se deconnecter</a>
            <a id="home" href="index.php">Accueil</a>
        </div>
    </div>
    <div class="row allPictures">
        <div class="col-xs-12 pagination">
            <?php foreach ($photos as $photo):?>
                <a href="picture.php?id=<?=$photo['id']?>">
                    <img id="picture" src="img/uploads/<?php echo $photo['id']?>.png">
                </a>
            <?php endforeach;?>
            <?php for ($i=1;$i<=$allPage;$i++){
                if ($i == $cPage)
                    echo "$i/";
                else
                    echo "<a href=\"gallery.php?p=$i\">$i</a>/";
            }?>
        </div>
    </div>
</div>
</body>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>