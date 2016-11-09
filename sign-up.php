<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>sign-up</title>
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body
<header>
    <div id="logo">
        <h1><a href="sign-up.php">Inscription</a></h1>
    </div>
</header>
<form id="signupForm" method="post" action="index.php">
    <div id="register">
        <label class="col-xs-12">Email</label><br/>
        <input type="text" name="email" placeholder="email"><br/>
        <label class="col-xs-12">Utilisateur</label><br/>
        <input type="text" name="login" placeholder="utilisateur"><br/>
        <label class="col-xs-12">Mot de passe</label><br/>
        <input type="password" name="password" placeholder="mot de passe"><br/>
        <label class="col-xs-12">Confirmer mot de passe</label><br/>
        <input type="password" name="password-conf" placeholder="mot de passe"><br/>
        <input type="submit" name="valider" value="Souriez">
    </div>
</form>
</body>
</html>

<?php

include 'config/setup.php';

if (!empty($_POST))
{
    $db = dbConnect();
}
?>