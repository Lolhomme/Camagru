<?php
require_once ("dbConnect.php");
session_start();

$errors = array();

if (!empty($_POST))
{
    $username =
    if (empty($_POST['username']) || empty($_POST['password']))
        $errors['emptyField'] = true;
    if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45)
        $errors['invalidUsernameForm'] = true;
    if (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 255)
        $errors['invalidPasswordForm'] = true;

    if (empty($errors))
    {

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
            ?>
        </div>
        <form id="signinForm" method="post" action="home.php">
            <div id="log">
                <div class="box">
                    <input type="text" name="login" placeholder="Nom d'utilisateur">
                </div>
                <div class="box">
                    <input type="password" name="password" placeholder="Mot de passe">
                </div>
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