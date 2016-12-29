<?php

require_once ("includes/dbConnect.php");

$errors = array();
if (!empty($_POST))
{
    $email = htmlspecialchars($_POST['email']);
    $req = $db->prepare("select count (*) from users where email=:email");
    $req->bindValue(':email', $email);
    $req->execute();

    $req2 = $db->prepare("select active from users where email=:email");
    $req2->bindValue(':email', $email);
    $req2->execute();
    //All errors
    if ($req->fetchColumn() == 0)
        $errors['emailDontExist'] = true;
    if ($req2->fetchColumn()!= "1");
        $errors['Compte inactif ou mot de passe déjà reset'] = true;
}
    if (empty($errors))
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
        </div>
        <form id="ForgotPassword" method="post">
            <div id="log">
                <div class="box">
                    <input type="email" name="email" placeholder="Email">
                </div>
                <div class="box">
                    <button type="submit" name="reset">Reset le mot de passe</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
