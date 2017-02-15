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
        header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/login.php?error=invalid_link');
        die();
    }

    if (empty($errors)) {
        $username = trim(htmlspecialchars($_POST['username']));
        //Password set
        $salt = hash("sha256", $_POST['username'].time());
        $password = hash("sha256", $_POST['newpassword'].$salt);
        $req = $db->prepare("update users set salt=:salt, password=:password, confirmKey=1 where confirmKey=:recover and username=:username");
        $req->bindValue(':password', $password);
        $req->bindValue(':username', $username);
        $req->bindValue(':salt', $salt);
        $req->bindValue(':recover', $_GET['v']);
        if ($req->execute())
        {
            header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/login.php?ok=passwordChange');
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>sign-up</title>
    <link href="css/grid.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/forgot-recover.css">
</head>
<header>
    <h1>Camagru</h1>
</header>
<body>
<div class="container">
    <div class="row nav">
        <a href="index.php">Camagru</a>
    </div>
    <div class="row">
    <div class="col-xs-12" style="height: 50px;"></div>
    </div>
    <form method="post" id="signupForm">
            <div class="row forgot-form">
                <input class="col-xs-12 col-sm-4 col-sm-push-4" type="text" name="username" placeholder="Nom d'utilisateur" autofocus>
                <div class="col-xs-12" style="height: 10px;"></div>
                <input class="col-xs-12 col-sm-4 col-sm-push-4" type="password" name="newpassword" placeholder="Nouveau mot de passe">
                <div class="col-xs-12" style="height: 10px;"></div>
                <button class="col-xs-12 col-sm-4 col-sm-push-4" type="submit" name="reset">Changer le mot de passe</button>
            </div>
            <div class="row" id="errors">
                <?php
                echo '<div class="col-xs-12 col-sm-4 col-sm-push-4">';
                if (isset($errors['emptyField']))
                    echo "<h4>Tous les champs sont obligatoires.</h4>";
                else if (isset($errors['sameUsernamePassword']))
                    echo "<h4>Le mot de passe et le nom d'utilisateur ne peuvent être identiques.</h4>";
                if (isset($errors['invalidUsernameForm']))
                    echo "<h4>Format nom d'utilisateur invalide.</h4>";
                if (isset($errors['invalidPasswordForm']))
                    echo "<h4>Format mot de passe invalide.</h4>";
                echo '</div>';
                ?>
            </div>
        </form>
   </div>
<div class="col-xs-12" style="height: 200px"></div>
</body>
<footer>
    <h4><a target="_blank" href="https://github.com/Lolhomme">LAULOM Anthony</a></h4>
</footer>
</html>