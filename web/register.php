<?php

require '../config/setup.php';
include './views/sign-up.php';

/*if (!isset($_POST['email']) || !isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['password-conf']))
{
    echo 'Tous les champs sont obligatoires';
    die();
}*/

if ($_POST['username'] == $_POST['password'])
{
    echo 'Le nom d utilisateur et le mot de passe ne peuvent pas être identiques';
    die();
}
?>