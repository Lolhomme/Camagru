<?php
    if (isset($_SESSION['user'])) {
        if (!empty($_POST)) {
            if (empty($errors)) {
                $tmp_Upl_img = imagecreatefromstring(file_get_contents($_FILES['fileToUpload']['tmp_name']));

                $req = $db->prepare('insert into pictures (users_id) values (:users_id)');
                $req->bindValue(':users_id', $_SESSION['user']['id'], \PDO::PARAM_INT);
                if ($req->execute()) {
                    $picture_id = $db->lastInsertId();
                    imagepng($tmp_Upl_img, './img/uploads/' . $picture_id . '.png');
                    imagedestroy($tmp_Upl_img);
                    header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php');
                }
            }
        }
    }
?>