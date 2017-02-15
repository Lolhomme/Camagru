<?php
require_once("dbConnect.php");

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
        $req = $db->prepare("UPDATE users SET activate = 1 WHERE username=:username");
        $req->bindValue(':username', $login);
        $req->execute();

        header('location:'.$_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'/login.php?success=activation');
    }
    else
        header('location:' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/login.php?error=activation');
?>