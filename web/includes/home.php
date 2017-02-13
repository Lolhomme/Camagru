<?php
require ('dbConnect.php');
require ('anti-csrf.php');
session_start();

$errors = array();
$token = create_token();
if (isset($_SESSION['user'])) {
    if (!empty($_POST)) {
        if (check_token(1800, 'http://localhost:8081/index.php')) {
            if (empty($_POST['filterId']) && empty($_POST['filterId2']))
                $errors['noFilter'] = true;
            if (empty($_FILES)) { /*Upload from webcam*/

                if (empty($errors)) {

                    /*Creation image*/
                    try {
                        $tmp_img = imagecreatefromstring(base64_decode(explode(',', $_POST['base-img'])[1]));
                    } catch (\Exception $e) {
                        $errors['content'] = true;
                    }
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

                    if (empty($errors)) {

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
            } else { /*Upload from hard drive*/

                /*All errors*/
                if ($_FILES['file-to-upload']['error'] > 0)
                    $errors['upload'] = true;
                if ($_FILES['file-to-upload']['type'] != 'image/jpeg' && $_FILES['file-to-upload']['type'] != 'image/png')
                    $errors['type'] = true;
                if ($_FILES['file-to-upload']['size'] > 1048576)
                    $errors['size'] = true;

                if (empty($errors)) {

                    try {
                        $tmp_img = imagecreatefromstring(file_get_contents($_FILES['file-to-upload']['tmp_name']));
                    } catch (\Exception $e) {
                        $errors['content'] = true;
                    }
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

                    if (empty($errors)) {
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
        }
        else
            $errors['token'] = true;
    }

    /*Pagination*/
    $req = $db->prepare('select count(id) from pictures');
    $req->execute();
    $row = $req->fetchColumn();
    $nbrPerPage = 12;
    $allPage = ceil($row / $nbrPerPage);
    if (isset($_GET['p']) && ($_GET['p'] > 0 && $_GET['p'] <= $allPage))
        $firstPage = $_GET['p'];
    else
        $firstPage = 1;
    $cPage = (($firstPage - 1) * $nbrPerPage);

    /*Get photo user*/
    $req = $db->prepare("select id from pictures where users_id=:users_id ORDER BY created_at DESC LIMIT $cPage,$nbrPerPage");
    $req->bindValue(':users_id', $_SESSION['user']['id']);
    if ($req->execute() && $row = $req->fetchAll())
        $photos = $row;
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
    <div class="row" id="errors">
        <?php
        echo '<div class="col-xs-12 col-sm-4 col-sm-push-4">';
        if (isset($errors['token']))
            echo '<h4>Erreur dans le formulaire ou session expirée.</h4>';
        if (isset($errors['type']))
            echo '<h4>Format non autorisé.</h4>';
        if (isset($errors['size']))
            echo '<h4>Fichier trop volumineux</h4>';
        if (isset($errors['creation']) || isset($errors['content']))
            echo '<h4>Une erreur est survenue lors de la creation de votre photo.</h4>';
        if (isset($errors['upload']))
            echo "<h4>Une erreur est survenue lors du téléchargement.</h4>";
        if (isset($errors['noFilter']))
            echo "<h4>Veuillez selectionner un filtre.</h4>";
        echo '</div>';
        ?>
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
            <div class="col-xs-12-nogutter col-sm-12-nogutter filters">
                <?php $i = 1;?>
                <?php for (;$i <= 3; $i++):?>
                    <img src="../img/filters/<?=$i?>.png" id="filter<?=$i?>" alt="<?=$i?>" style="border: 1px solid transparent">
                <?php endfor;?>
                <input id="nbrFilters" type="hidden" value="<?=$i - 1;?>">
            </div>
            <div class="col-xs-12 forms">
                <form id="formCam" method="post" enctype="multipart/form-data" style="display: none">
                    <input type="hidden" name="token" id="token" value="<?echo $_SESSION['token']?>">
                    <input type="hidden" id="base-img" name="base-img" value="none">
                    <input id="filter-id" type="hidden" name="filterId" value="0">
                    <button id="startbutton">Prendre une photo</button>
                    <button type="submit" id="savebutton" name="upload" style="display: none">Sauvegarder</button>
                </form>
                <form id="formUpl" method="post" enctype="multipart/form-data" style="display: none">
                    <input type="hidden" name="token" id="token" value="<?echo $_SESSION['token']?>">
                    <input type="hidden" name="max_file_size" value="1048576">
                    <input id="filter-id2" type="hidden" name="filterId2" value="0">
                    <input type="file" id="input-file" name="file-to-upload" accept="image/jpeg, image/png">
                    <button type="submit" id="savebutton_UP" name="upload-ext" style="display: none">Sauvegarder</button>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-sm-push-4 side">
            <?php if (!isset($photos))
                echo "<h4>Aucune ganache de vous</h4>";
            else
                echo "<h4>Vos ganaches</h4>";?>
            <?php if (is_array($photos))
                foreach ($photos as $photo) : ?>
            <a href="../picture.php?id=<?=$photo['id']?>">
                    <img id="last_photos" src="../img/uploads/<?php echo $photo['id']?>.png">
                <?php endforeach; ?>
                <?php for ($i=1;$i<=$allPage;$i++){
                    if ($i == $cPage)
                        echo "$i/";
                    else
                        echo "<a href=\"index.php?p=$i\">$i</a>/";
                }?>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/takepicture.js"></script>
</body>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>