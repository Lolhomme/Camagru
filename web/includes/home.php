<?php

$errors = array();

if (!empty($_POST) && isset($_SESSION['logged']))
{
    /*All errors*/

    if ($_POST['base-img'] == 'none')
        $errors['base-img'] = true;

    if (empty($errors))
    {
        /*Creation image*/

       $tmp_img = imagecreatefromstring(base64_decode(explode(',', $_POST['base-img'])[1]));
       $width = 640;
       $height = 480;
       /*$filter = imagecreatetruecolor($width, $height);
       $b = imagecopyresampled($tmp_img, $filter, 0, 0, 0, 0, $width, $height, $width, $height);*/

       /*Stocker la photo*/
       $id = $_SESSION['logged'];
       imagesavealpha($tmp_img, true);
       imagepng($tmp_img, './img/uploads/'.$id.'.png');
    }
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
        <div class="col-xs-12 col-sm-9 main">
            <video id="video"></video>
            <img src="#" id="photo" alt="photo" style="display: none">
            <canvas id="canvas"></canvas>
            <button id="startbutton" onclick="hiddenbutton()">Prendre une photo</button>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" id="base-img" name="base-img" value="none">
                <button type="submit" id="savebutton" name="upload" style="display: none">Sauvegarder</button>
            </form>
        </div>
        <div class="col-xs-12 col-sm-3 col-sm-push-3 side">Side</div>
    </div>
</div>
<script type="text/javascript" src="../js/home.js"></script>
</body>
<!--<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>-->
</html>