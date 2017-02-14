<?php

require_once ("dbConnect.php");
session_start();

$errors = array();
if (isset($_SESSION['user'])){
    header('location: index.php');
}
else {
    if (!empty($_POST)) {

        if (empty($_POST['username']) || empty($_POST['password']))
            $errors['emptyField'] = true;
        if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45)
            $errors['invalidUsernameForm'] = true;
        if (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 255)
            $errors['invalidPasswordForm'] = true;

        /*Get all user info*/
        $username = trim(htmlspecialchars($_POST['username']));
        $req = $db->prepare("select * from users where username=:username");
        $req->bindValue(':username', $username);
        if ($req->execute() && $row = $req->fetch())
            $user = $row;

        if (empty($errors)) {

            /*Check all*/
            $password = hash("sha256", $_POST['password'] . $row['salt']);
            if ($password != $user['password'])

                $errors['invalidLog'] = true;
            else {
                $_SESSION['user'] = $user;
                header('location:' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/index.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru</title>
        <link rel="stylesheet" type="text/css" href="../css/grid.css">
        <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>
<body>
<div class="container">
    <h1>Camagru</h1>
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-push-2 confirm-div">
                <?php
                if (isset($_GET['success']))
                    echo "<h4>Votre compte est activé, veuillez vous connecter.</h4>";
                if (isset($_GET['error']))
                    echo "<h4>Lien invalide.</h4>";
                if (isset($_GET['ok']))
                    echo "<h4>Mot de passe changé, veuillez vous connecter.</h4>";
                ?>
            </div>
        </div>
        <form id="signinForm" method="post">
            <div class="row connect-form">
                        <input class="col-xs-12 col-sm-4 col-sm-push-4" type="text" name="username" placeholder="Nom d'utilisateur" autofocus>
                        <div class="col-xs-12" style="height: 10px;"></div>
                        <input class="col-xs-12 col-sm-4 col-sm-push-4" type="password" name="password" placeholder="Mot de passe">
                        <div class="col-xs-12" style="height: 10px;"></div>
                        <button class="col-xs-12 col-sm-4 col-sm-push-4" class="login">Se connecter</button>
            </div>
            <div class="row">
                <div class="col-xs-12" style="height: 20px;"></div>
                <div class="col-xs-12 col-sm-4 col-sm-push-4">
                    <div class="link">
                    <a href="../sign-up.php">Pas encore inscrit? Viendez ça va être bien.</a>
                    </div>
                    <div class="link">
                        <a href="../forgot.php">Mot de passe oublié?</a>
                    </div>
                </div>
            </div>
            <div class="row" id="errors">
                <?php
                echo '<div class="col-xs-12 col-sm-4 col-sm-push-4">';
                if (isset($errors['emptyField']))
                    echo "<h4>Tous les champs sont obligatoires.</h4>";
                if (isset($errors['invalidLog']))
                    echo "<h4>Mauvais nom d'utilisateur ou mot de passe.</h4>";
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