<?php

require_once ("includes/dbConnect.php");
$errors = array();

if (!isset($_GET['v']) || isset($_GET['v']) && empty($_GET['v']))
{
    header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php');
    die();
}
if (!empty($_POST)) {
    //All errors
    if (empty($_POST['username']) || empty($_POST['newpassword']))
        $errors['emptyField'] = true;
    if (strlen($_POST['newpassword']) < 5 || strlen($_POST['newpassword']) > 255)
        $errors['invalidPasswordForm'] = true;
    if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45)
        $errors['invalidUsernameForm'] = true;
    if ($_POST['username'] == $_POST['newpassword'])
        $errors['sameUsernamePassword'] = true;
    $req = $db->prepare("select count(*) from users where confirmKey=:recover");
    $req->bindValue(':recover', $_GET['v']);
    $req->execute();
    if ($req->fetchColumn() == 0) {
        header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php?error=invalid_link');
        die();
    }

    if (empty($errors)) {
        $username = trim(htmlspecialchars($_POST['username']));
        //Password set
        $salt = hash("sha256", $_POST['username'].time());
        $password = hash("sha256", $_POST['newpassword'].$salt);

        $req = $db->prepare("update users set password=:password, confirmKey=1 where confirmKey=:recover and username=:username");
        $req->bindValue(':password', $password);
        $req->bindValue(':username', $username);
        $req->bindValue(':recover', $_GET['v']);
        if ($req->execute())
        {
            header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php?ok=passwordChange');
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru</title>
    <link rel="stylesheet" type="text/css" href="./css/index.css">
</head>
<body>
<div class="nav">
    <a href="index.php">Camagru</a>
</div>
<div class="container">
    <div class="login">
        <div id="logo">
            <h1 href="index.php">Camagru</h1>
        </div>
        <form id="ForgotPassword" method="post">
            <div id="log">
                <div class="errors">
                    <?php
                    if (isset($errors['emptyField']))
                        echo "<h4>Tous les champs sont obligatoires.</h4>";
                    else if (isset($errors['sameUsernamePassword']))
                        echo "<h4>Le mot de passe et le nom d'utilisateur ne peuvent être identiques.</h4>";
                    if (isset($errors['invalidUsernameForm']))
                        echo "<h4>Format invalide.</h4>";
                    ?>
                </div>
                <div class="box">
                    <input type="text" name="username" placeholder="Nom d'utilisateur">
                </div>
                <div class="box">
                    <div class="error">
                        <?php
                        if (isset($errors['invalidPasswordForm']))
                            echo "<h4>Format invalide.</h4>";
                        ?>
                    </div>
                    <input type="password" name="newpassword" placeholder="Nouveau mot de passe">
                </div>
                <div class="box">
                    <button type="submit" name="change">Changer le mot de passe</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
