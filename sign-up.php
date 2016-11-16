<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>sign-up</title>
    <link rel="stylesheet" type="text/css" href="css/sign-up.css">
</head>
<body>
<div class="container">
    <div id="register">
        <div id="logo">
            <h1 href="sign-up.php">Inscription</h1>
            <p class="logo-msg">Postes tes photos dégueulasses</p>
        </div>
        <form id="signupForm" method="post" action="index.php">
            <div class="box">
                <input type="text" name="email" placeholder="email">
            </div>
            <div class="box">
                <input type="text" name="login" placeholder="utilisateur">
            </div>
            <div class="box">
                <input type="password" name="password" placeholder="mot de passe">
            </div>
            <div class="box">
                <input type="password" name="password-conf" placeholder="mot de passe">
            </div>
            <div class="box">
                <input type="submit" name="valider" value="Souriez">
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php

include 'config/setup.php';

if (!empty($_POST))
{
    $db = dbConnect();
}
?>