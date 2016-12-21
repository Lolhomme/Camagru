<?php
require_once ("dbConnect.php");

$login = $_GET['log'];
$confirmKey = $_GET['confirmKey'];
$req = $db->prepare("SELECT confirmKey, activate FROM users WHERE username=:username");

//Get confirm key and login from DataBase
if ($req->execute(array(':username' => $login)) && $row = $req->fetch())
{
    $confirmKeyDB = $row['confirmKey'];
    $activate = $row['activate'];
}


if ($activate == '1')
    echo 'Ce compte est déjà activé';
else
    if ($confirmKey == $confirmKeyDB && !empty($confirmKeyDB))
    {
        echo 'Votre compte est activé';

        $req = $db->prepare("UPDATE users SET activate = 1 WHERE username=:username");
        $req->bindValue(':username', $login);
        $req->execute();
    }
    else
        echo 'ERROR';
?>