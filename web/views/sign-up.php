<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>sign-up</title>
    <link rel="stylesheet" type="text/css" href="../css/sign-up.css">
</head>
<body>
<div class="nav">
    <a href="../index.php">Camagru</a>
</div>
<div class="container">
    <div id="register">
        <div id="logo">
            <h1 href="sign-up.php">Inscription</h1>
            <p class="logo-msg">Postes tes photos dégueulasses</p>
        </div>
        <form method="post" id="signupForm">
            <div class="box">
                <input type="text" name="email" placeholder="Email" onblur="verifMail(this)">
            </div>
            <div class="box">
                <input type="text" name="username" placeholder="Utilisateur" onblur="verifPseudo(this)">
            </div>
            <div class="box">
                <input type="password" name="password" placeholder="Mot de passe">
            </div>
            <div class="box">
                <input type="password" name="password-conf" placeholder="Confirmer le mot de passe">
            </div>
            <div class="box">
                <button type="submit">Souriez</button>
            </div>
        </form>
    </div>
</div>
</body>
<script>
    function surligne(champ, erreur)
    {
        if(erreur)
            champ.style.backgroundColor = "#fba";
        else
            champ.style.backgroundColor = "";
    }

    function verifMail(champ)
    {
        var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
        if(!regex.test(champ.value))
        {
            surligne(champ, true);
            return false;
        }
        else
        {
            surligne(champ, false);
            return true;
        }
    }
    function verifPseudo(champ)
    {
        if(champ.value.length < 2 || champ.value.length > 25)
        {
            surligne(champ, true);
            return false;
        }
        else
        {
            surligne(champ, false);
            return true;
        }
    }
</script>
</html>

<?php

//require_once '../dbConnect.php';
/*error_reporting(E_ALL);

if(!empty($_POST))
{
    foreach($_POST as $key=>$val)
    {
        if(empty($val))
        {
            echo 'Tous les champs sont obligatoires';
            die();
        }
    }
}*/
?>