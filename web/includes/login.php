<?php
require ("dbConnect.php");
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
            <?php
            if (isset($_GET['success']))
                echo "<h4>Votre compte est activé, veuillez vous connecter.</h4>";
            ?>
        </div>
        <form id="signinForm" method="post" action="../sign-in.php">
            <div id="log">
                <div class="box">
                    <input type="text" name="login" placeholder="Nom d'utilisateur">
                </div>
                <div class="box">
                    <input type="password" name="password" placeholder="Mot de passe">
                </div>
                <div class="box">
                    <button type="submit" name="log-in">Se connecter</button>
                </div>
            </div>
        </form>
        <div class="or">OU</div>
        <form action="../sign-up.php" method="post">
            <div class="box">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>