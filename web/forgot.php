<?php

require_once ("includes/dbConnect.php");

$errors = array();
if (!empty($_POST)) {
    $mail = htmlspecialchars($_POST['email']);
    $req = $db->prepare("select count(*) from users where mail=:mail");
    $req->bindValue(':mail', $mail);
    $req->execute();

    $req2 = $db->prepare("select activate from users where mail=:mail");
    $req2->bindValue(':mail', $mail);
    $req2->execute();

    //All errors
    if ($req->fetchColumn() == 0)
        $errors['emailDontExist'] = true;
    /*if ($req2->fetchColumn() != "1") ;
        $errors['inactiveAccount'] = true;*/

    $recover = hash("md5", time());
    $link = ($_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/recover.php?v=' . $recover);

    //Recovery email
    $destinataire = $_POST['email'];
    $subject = 'Mot de passe oublié.';
    $header = 'De Camagru tête de cul';
    $message = "Vous recevez ce mail car vous avez oublié votre mot de passe. Si ce n\'est pas le cas, n\'en tenez pas compte.
    
    Pour réinitialiser votre mot de passe veuillez cliquer ici :
    
    '$link'
    -----------------------
     Ceci est un email automatique, veuillez ne pas y répondre.";

    if (empty($errors)) {
        $req = $db->prepare("update users set confirmKey=:recover where mail=:mail");
        $req->bindValue(':recover', $recover);
        $req->bindValue(':mail', $mail);
        if ($req->execute()) {
            mail($destinataire, $subject, $header, $message);
            $success = 'Un email vous a été envoyé.';
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
<body>
<div class="container">
    <div class="row nav">
        <a href="index.php">Camagru</a>
    </div>
    <div class="row confirm-div">
        <div class="col-xs-12 col-sm-8 col-sm-push-2 confirm-div">
            <?php
            if (isset($success))
                echo "<h4>$success</h4>";
            ?>
        </div>
    </div>
    <form method="post" id="signupForm">
        <div class="row forgot-form">
            <input class="col-xs-12 col-sm-4 col-sm-push-4" type="email" name="email" placeholder="Email" autofocus>
            <div class="col-xs-12" style="height: 10px;"></div>
            <button class="col-xs-12 col-sm-4 col-sm-push-4" type="submit" name="reset">Reset le mot de passe</button>
        </div>
        <div class="row" id="errors">
            <?php
            echo '<div class="col-xs-12 col-sm-4 col-sm-push-4">';
                    if (isset($errors['emailDontExist']))
                        echo "<h4>Cet email n'est associé a aucun compte.</h4>";
                    else if (isset($errors['inactiveAccount']))
                        echo "<h4>Compte inactif ou mot de passe déjà reset.</h4>";
            echo '</div>';
            ?>
        </div>
    </form>
</div>
</body>
</html>