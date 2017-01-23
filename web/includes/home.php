<?php

require ('dbConnect.php');
require ('getPicture.php');
session_start();
$errors = array();

print_r($_FILES);

if (isset($_SESSION['user'])) {
    if (!empty($_POST)) {
        if (empty($errors)) {

            /*Creation image*/
//            $tmp_img = imagecreatefromstring(base64_decode(explode(',', $_POST['base-img'])[1]));
            $tmp_Upl_img = imagecreatefromstring(file_get_contents($_FILES['fileToUpload']['tmp_name']));
            /*$width = 640;
            $height = 480;*/

            /*Enregistre l'adresse de la photo dans la DB*/
            $req = $db->prepare('insert into pictures (users_id) values (:users_id)');
            $req->bindValue(':users_id', $_SESSION['user']['id'], \PDO::PARAM_INT);
            if ($req->execute()) {

                /*Envoi de la photo dans un dossier côté server et destruction de l'image tmp*/
                $picture_id = $db->lastInsertId();
                imagepng($tmp_Upl_img, './img/uploads' . $picture_id .'png');
//                imagepng($tmp_img, './img/uploads/' . $picture_id . '.png');
//                imagedestroy($tmp_img);
                imagedestroy($tmp_Upl_img);
            }
        }
    }
    /*Get photo user*/
    $req = $db->prepare('select id from pictures where users_id=:users_id ORDER BY created_at DESC');
    $req->bindValue(':users_id', $_SESSION['user']['id']);
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
    <title>Camagru-Home</title>
    <link href="../css/grid.css" type="text/css" rel="stylesheet">
    <link href="../css/home.css" type="text/css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1>Camagru: Bienvenue ganache!</h1>
    <div class="row nav">
        <div class="col-xs-12 col-sm-12">
            <a id="logout" href="./includes/logout.php">Se deconnecter</a>
            <a id="gallery">Gallerie</a>
        </div>
    </div>
    <div class="row display">
        <div class="col-xs-12 col-sm-8 main">
            <video id="video"></video>
            <img src="#" id="photo" alt="photo" style="display: none">
            <canvas id="canvas" style="display: none"></canvas>
            <div id="preview" style="display: none"></div>
            <button id="startbutton" onclick="hiddenbutton()">Prendre une photo</button>
            <form id="upload-area" method="post" enctype="multipart/form-data">
                <input type="hidden" id="base-img" name="base-img" value="none">
                <input type="hidden" name="max_file_size" value="1048576">
                <input type="file" id="input-file" name="file-to-upload" accept="image/jpeg, image/png">
                <button type="submit" id="savebutton" name="upload" style="display: none">Sauvegarder</button>
            </form>
        </div>
        <div class="col-xs-12 col-sm-4 col-sm-push-4 side">
            <?php if (isset($noUploads))
            echo "<h4>Aucune ganache de vous</h4>";?>
            <?php if (is_array($photos))
                foreach ($photos as $photo) : ?>
            <img id="last_photos" src="../img/uploads/<?php echo $photo['id']?>.png">
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/home.js"></script>
</body>
<!--<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>-->
</html>