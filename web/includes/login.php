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


        $password = hash("sha256", $_POST['password'].$salt);
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
    <link rel="stylesheet" type="text/css" href="../css/index.css">
</head>
<style>
</style>
<body>
<div class="container">
    <div class="login">
        <div id="logo">
            <h1 href="index.php">Camagru</h1>
            <?php
            if (isset($_GET['success']))
                echo "<h4>Votre compte est activé, veuillez vous connecter.</h4>";
            if (isset($errors['emptyField']))
                echo "<h4>Tous les champs sont obligatoires.</h4>";
            if (isset($errors['invalidLog']))
                echo "<h4>Mauvais nom d'utilisateur ou mot de passe.</h4>";
            ?>
        </div>
        <form id="signinForm" method="post">
            <div id="log">
                <div class="box">
                    <?php
                    if (isset($errors['invalidUsernameForm']))
                        echo "<h4>Format invalide.</h4>"
                    ?>
                    <input type="text" name="username" placeholder="Nom d'utilisateur">
                </div>
                <div class="box">
                    <?php
                    if (isset($errors['invalidUsernameForm']))
                        echo "<h4>Format invalide.</h4>"
                    ?>
                    <input type="password" name="password" placeholder="Mot de passe">
                </div>
                <a class="forgot" href="../forgot.php">Mot de passe oublié?</a>
                <div class="box">
                    <button type="submit" name="log-in">Se connecter</button>
                </div>
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
</body>
</html>