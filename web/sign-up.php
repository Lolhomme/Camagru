<?php

require_once ("dbConnect.php");

error_reporting(E_ALL);

if (!empty($_POST))
{
    if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password-conf']))
        echo 'Tous les champs sont obligatoires';

    else if ($_POST['username'] == $_POST['password'])
        echo 'Le mot de passe et le nom d\'utilisateur ne peuvent être identiques';

    else if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45 || (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 255)) {
        echo 'Le nom d\'utilisateur doit faire entre 5 et 45 caractères<br>';
        echo 'Le mot de passe doit faire entre 5 et 255 caractères';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>sign-up</title>
    <link rel="stylesheet" type="text/css" href="css/sign-up.css">
</head>
<body>
<div class="nav">
    <a href="index.php">Camagru</a>
</div>
<div class="container">
    <div id="register">
        <div id="logo">
            <h1 href="sign-up.php">Inscription</h1>
            <p class="logo-msg">Postes tes photos dégueulasses</p>
        </div>
        <form method="post" id="signupForm" onsubmit="verifForm(this)">
            <div class="box">
                <input type="text" name="email" placeholder="Email" onchange="verifMail(this)">
            </div>
            <div class="box">
                <input type="text" name="username" placeholder="Utilisateur" onchange="verifPseudo(this)">
            </div>
            <div class="box">
                <input type="password" name="password" placeholder="Mot de passe" onchange="verifPseudo(this)">
            </div>
            <div class="box">
                <input type="password" name="password-conf" placeholder="Confirmer le mot de passe" onchange="verifPseudo(this)">
            </div>
            <div class="box">
                <button type="submit" name="valider">Souriez</button>
            </div>
        </form>
    </div>
</div>
</body>
<script>
    function verifForm(f)
    {
        var pseudoOk = verifPseudo(f.pseudo);
        var mailOk = verifMail(f.email);
        var passwordOk = verifPseudo(f.pseudo);

        if(pseudoOk && mailOk && passwordOk)
            return true;
        else
        {
            return false;
        }
    }
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