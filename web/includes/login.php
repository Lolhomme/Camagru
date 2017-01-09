<?php

require_once ("dbConnect.php");
session_start();

$errors = array();

if (!empty($_POST))
{

    if (empty($_POST['username']) || empty($_POST['password']))
        $errors['emptyField'] = true;
    if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45)
        $errors['invalidUsernameForm'] = true;
    if (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 255)
        $errors['invalidPasswordForm'] = true;

    if (empty($errors))
    {
        $username = trim(htmlspecialchars($_POST['username']));

        //Get salt from DataBase
        $req = $db->prepare("select salt from users where username=:username");
        $req->bindValue(':username', $username);
        if ($req->execute() && $row = $req->fetch())
            $salt = $row['salt'];

        $password = hash("sha256", $_POST['password'] .$salt);
        $req = $db->prepare("select count(*) from users where username=:username and password=:password and activate = 1");
        $login = array(':username' => $username,
                       ':password' => $password);
        $req->execute($login);
        if ($req->fetchColumn() == 0)
            $errors['invalidLog'] = true;
        else
        {
            $_SESSION['logged'] = $username;
            header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Camagru</title>
<!--    <link rel="stylesheet" type="text/css" href="../css/index.css">-->
        <link rel="stylesheet" type="text/css" href="../css/grid.css">
        <link rel="stylesheet" type="text/css" href="../css/login.css">
    <link media="screen"
</head>
<style>
</style>
<body>
<div class="container">
    <h1>Camagru</h1>
    <div class="login">
        <div class="row">
            <div class="c-12">
                <?php
                if (isset($_GET['success']))
                    echo "<h4>Votre compte est activé, veuillez vous connecter.</h4>";
                if (isset($_GET['error']))
                    echo "<h4>Lien invalide.</h4>";
                if (isset($_GET['ok']))
                    echo "<h4>Mot de passe changé, veuillez vous connecter.</h4>";
                if (isset($errors['emptyField']))
                    echo "<h4>Tous les champs sont obligatoires.</h4>";
                if (isset($errors['invalidLog']))
                    echo "<h4>Mauvais nom d'utilisateur ou mot de passe.</h4>";
                ?>
            </div>
        </div>
        <form id="signinForm" method="post">
            <div class="row">
                <div class="c-12">
                        <?php
                        if (isset($errors['invalidUsernameForm']))
                            echo "<h4>Format invalide.</h4>"
                        ?>
                        <input type="text" name="username" placeholder="Nom d'utilisateur">
                </div>
                        <?php
                        if (isset($errors['invalidUsernameForm']))
                            echo "<h4>Format invalide.</h4>"
                        ?>
                        <input type="password" name="password" placeholder="Mot de passe">
            </div>
            <div class="row">
                    <a class="forgot" href="../forgot.php">Mot de passe oublié?</a>
            </div>
                    <div class="box">
                        <button type="submit" name="log-in">Se connecter</button>
                    </div>
        </form>
        <div class="or">OU</div>
        <form action="../sign-up.php" method="post">
            <div class="box">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</body>
</html>