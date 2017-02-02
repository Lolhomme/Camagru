<?php
require ('dbConnect.php');
session_start();
$errors = array();
if (isset($_SESSION['user'])) {
    if (!empty($_POST)) {
        if (empty($_FILES)) { /*Upload from webcam*/
            if (empty($errors)) {

                /*Creation image*/
                $tmp_img = imagecreatefromstring(base64_decode(explode(',', $_POST['base-img'])[1]));
                $filter = './img/filters/' . $_POST['filterId'] . '.png';
                $width = 640;
                $height = 480;
                list($filter_w, $filter_h) = getimagesize($filter);
                $tmp_filter = imagecreatefrompng($filter);
                $true_filter = imagecreatetruecolor($width, $height);
                imagealphablending($true_filter, false);
                imagesavealpha($true_filter, true);
                imagecolortransparent($true_filter);
                $a = imagecopyresampled($true_filter, $tmp_filter, 0, 0, 0, 0, $width, $height, $filter_w, $filter_h);
                $b = imagecopyresampled($tmp_img, $true_filter, 0, 0, 0, 0, $width, $height, $width, $height);

                if ($a == false || $b == false)
                    $errors['creation'] = true;
                imagedestroy($tmp_filter);
                imagedestroy($true_filter);

                /*Enregistre l'adresse de la photo dans la DB*/
                $req = $db->prepare('insert into pictures (users_id) values (:users_id)');
                $req->bindValue(':users_id', $_SESSION['user']['id'], \PDO::PARAM_INT);
                if ($req->execute()) {

                    /*Envoi de la photo dans un dossier côté server et destruction de l'image tmp*/
                    $picture_id = $db->lastInsertId();
                    imagepng($tmp_img, './img/uploads/' . $picture_id . '.png');
                    imagedestroy($tmp_img);
                }
            }
        }
        else { /*Upload from hard drive*/
            if (empty($errors)) {

                $tmp_img = imagecreatefromstring(file_get_contents($_FILES['file-to-upload']['tmp_name']));
                $filter = './img/filters/' . $_POST['filterId2'] . '.png';
                list($width, $height) = getimagesize($_FILES['file-to-upload']['tmp_name']);
                list($filter_w, $filter_h) = getimagesize($filter);
                $tmp_filter = imagecreatefrompng($filter);
                $true_filter = imagecreatetruecolor($width, $height);
                imagealphablending($true_filter, false);
                imagesavealpha($true_filter, true);
                imagecolortransparent($true_filter);
                $a = imagecopyresampled($true_filter, $tmp_filter, 0, 0, 0, 0, $width, $height, $filter_w, $filter_h);
                $b = imagecopyresampled($tmp_img, $true_filter, 0, 0, 0, 0, $width, $height, $width, $height);

                if ($a == false || $b == false)
                    $errors['creation'] = true;
                imagedestroy($tmp_filter);
                imagedestroy($true_filter);
                $req = $db->prepare('insert into pictures (users_id) values (:users_id)');
                $req->bindValue(':users_id', $_SESSION['user']['id'], \PDO::PARAM_INT);
                if ($req->execute()) {
                    $picture_id = $db->lastInsertId();
                    imagepng($tmp_img, './img/uploads/' . $picture_id . '.png');
                    imagedestroy($tmp_img);
                }
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
            <a id="gallery" href="../gallery.php">Gallerie</a>
        </div>
    </div>
    <div class="row display">
        <div class="col-xs-12 col-sm-8 main">
            <video id="video"></video>
            <div id="previewCam" style="display: none">
                <img src="#" id="photo" alt="photo" style="display: none">
                <canvas id="canvas"></canvas>
            </div>
            <div id="preview" style="display: none">
            </div>
            <div class="col-xs-12 filters">
                <?php $i = 1;?>
                <?php for (;$i <= 3; $i++):?>
                    <img src="../img/filters/<?=$i?>.png" id="filter<?=$i?>" alt="<?=$i?>" style="border: 1px solid transparent">
                <?php endfor;?>
                <input id="nbrFilters" type="hidden" value="<?=$i - 1;?>">
            </div>
            <form id="formCam" method="post" enctype="multipart/form-data" style="display: none">
                <input type="hidden" id="base-img" name="base-img" value="none">
                <input id="filter-id" type="hidden" name="filterId" value="0">
                <button id="startbutton">Prendre une photo</button>
                <button type="submit" id="savebutton" name="upload" style="display: none">Sauvegarder</button>
            </form>
            <form id="formUpl" method="post" enctype="multipart/form-data" style="display: none">
                <input type="hidden" name="max_file_size" value="1048576">
                <input id="filter-id2" type="hidden" name="filterId2" value="0">
                <input type="file" id="input-file" name="file-to-upload" accept="image/jpeg, image/png">
                <button type="submit" id="savebutton_UP" name="upload-ext" style="display: none">Sauvegarder</button>
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
<script type="text/javascript" src="../js/takepicture.js"></script>
</body>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>