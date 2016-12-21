<?php

require_once ("dbConnect.php");
$errors  = array();

if (!empty($_POST)) {
    //User set
    $username = trim(htmlspecialchars($_POST['username']));
    $req = $db->prepare("SELECT COUNT(*) FROM users WHERE username=:username");
    $req->bindValue(':username', $username);
    $req->execute();

    //Email set
    $mail = htmlspecialchars($_POST['email']);
    $req = $db->prepare("SELECT COUNT(*) FROM users WHERE mail=:mail");
    $req->bindValue(':mail', $mail);
    $req->execute();

    //Password set
    $salt = hash("sha256", $_POST['username'].time());
    $password = hash("sha256", $_POST['password'].$salt);

    //Key confirm set
    $confirmKey = hash("sha1", $_POST['username']);

    //Confirmation mail
    $destinataire = $_POST['email'];
    $username = $_POST['username'];

    $subject = "Activation de votre compte";
    $header = "De Camagru tête de cul";
    $message = 'Bonjour du gland,
    
    Pour activer votre compte veuillez cliquer sur le lien ci-dessous, ou le copier/coller dans votre navigateur.
     
     '.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/activation.php?log='.urldecode($username).'$confirmKey'.urldecode($confirmKey).'
     
     -----------------------
     Ceci est un email automatique, veuillez ne pas y répondre.';

    //All errors
    if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password-conf']))
        $errors['emptyField'] = true;
    if ($req->fetchColumn() > 0)
        $errors['emailExist'] = true;
    if ($req->fetchColumn() > 0)
        $errors['usernameExist'] = true;
    if ($_POST['username'] == $_POST['password'])
        $errors['sameUsernamePassword'] = true;
    if (strlen($_POST['username']) < 5 || strlen($_POST['username']) > 45)
        $errors['invalidUsernameForm'] = true;
    if (strlen($_POST['password']) < 5 || strlen($_POST['password']) > 255)
        $errors['invalidPasswordForm'] = true;

    //DataBase set
    if (empty($errors))
    {
        $req = $db->prepare("INSERT INTO users (username, mail, password, salt, confirmKey) VALUES (:username, :mail, :password, :salt, :confirmKey)");
        $user = array(':username' => $username,
                      ':mail' => $mail,
                      ':password' => $password,
                      ':salt' => $salt,
                      ':confirmKey' => $confirmKey);
        if ($req->execute($user)) {
            echo 'good';
            mail($destinataire, $subject, $header, $message);
        }
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
            <div class="errors">
                <?php
                if (isset($errors['emptyField']))
                    echo '<h5>Tous les champs sont obligatoires</h5>';
                if (isset($errors['sameUsernamePassword']))
                    echo '<h5>Le nom d\'utilisateur et le mot de passe ne peuvent être identiques</h5>'?>
            </div>
            <div class="box">
                <div class="errors">
                    <?php
                    if (isset($errors['emailExist']))
                        echo '<h5>Un autre compte utilise cet email</h5>'?>
                </div>
                <input type="email" name="email" placeholder="Email" onchange="verifMail(this)">
            </div>
            <div class="box">
                <div class="errors">
                <?php
                if (isset($errors['usernameExist']))
                    echo '<h5>Nom d\'utilisateur déjà pris</h5>';
                if (isset($errors['invalidUsernameForm']))
                    echo '<h5>Format invalide</h5>'?>
                </div>
                <input type="text" name="username" placeholder="Utilisateur" onchange="verifPseudo(this)">
            </div>
            <div class="box">
                <div class="errors">
                    <?php
                    if (isset($errors['invalidPasswordForm']))
                        echo '<h5>Format invalide</h5>'?>
                </div>
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